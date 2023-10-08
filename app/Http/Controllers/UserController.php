<?php

namespace App\Http\Controllers;

use App\Events\AddGiftEvent;
use App\Http\Requests\UpdateUserImageRequest;
use App\Http\Requests\UpdateUserRequest;
use App\Http\Resources\UserResource;
use App\Models\Gift;
use App\Models\GiftUser;
use App\Models\Level;
use App\Models\LevelUser;
use App\Models\User;
use App\Models\UserPointHistory;
use GuzzleHttp\Promise\Create;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class   UserController extends Controller
{
    public function index(){
        $users = User::all();
        return UserResource::collection($users);
    }

    public function show(User $user){
        return UserResource::make($user);
    }

    public function update(UpdateUserRequest $request , User $user){
        $user->update($request->all());
        return UserResource::make($user);
    }

    public function destroy(User $user){
        $user->delete();
        return response()->json(['message' => 'User Has Been Deleted Successfully']);
    }

    public function updateImage(UpdateUserImageRequest $request , User $user){
//      $request->validated($request->all());
        $user->update($request->all());
        return UserResource::make($user);
    }

    public function profile(){
        $stillToNextLevel = 0;
        $currentPoint = Auth::user()->points;
        $nextLevel = Level::where('start_points', '>', $currentPoint)->orderBy('start_points')->first();
        if($nextLevel){
            $stillToNextLevel = $nextLevel->start_points - $currentPoint;
        }
        return UserResource::make(Auth::user())
        ->additional([
            'still_to_next_level' => $stillToNextLevel,
            'next_level' => $nextLevel,
        ]);
    }

    public function addPointForUser(Request $request , User $user){
        $request->validate([
           'total' => 'required|numeric'
        ]);

        $points = ($request->total * 10) / 100 ;
        $oldPoints = $user->points;
        $user->update([
            'points' => $points + $oldPoints
        ]);

        UserPointHistory::create([
            'user_id' => $user->id,
            'points' => $user->points,
            'change' => $user->points - $oldPoints,
            'signal'=> '+'
        ]);

//        $level = Level::where('start_points' ,'<=' ,$user->points)
//        ->where('end_points' ,'>=' , $user->points)->first();
//        if ($level){
//            $levelUser = LevelUser::where('user_id' , $user->id)->orderBy('id' , 'desc')->first();
//
//            $currentLevel = Level::where('id' , $levelUser->level_id )->first();
//
//            if($level->id !== $currentLevel->id){
//              LevelUser::create([
//                'user_id' => $user->id,
//                'level_id' => $level->id
//              ]);
//            }
//        }

        $level = Level::where('start_points', '<=', $user->points)
            ->where('end_points', '>=', $user->points)
            ->first();

        $levelUser = LevelUser::where('user_id', $user->id)->latest()->first();

        if ($level) {
            if ($level->id !== $levelUser->level_id) {
                LevelUser::create([
                    'user_id' => $user->id,
                    'level_id' => $level->id
                ]);
            }
        }
        return UserResource::make($user);

    }


    // public function addGiftForUser(Request $request , User $user){
    //     $request->validate([
    //         'gift_id' => ['required' , Rule::exists('gifts' , 'id')],
    //         'quantity' => 'required|numeric|min:1'
    //     ]);
    //     $gift = Gift::where('id' , $request->gift_id)->first();
    //     $oldPoints = $user->points;

    //     UserPointHistory::create([
    //         'user_id' => $user->id,
    //         'points' => $user->points,
    //         'change' => $oldPoints - $user->points,
    //         'signal'=> '-'
    //     ]);

    //     $level = Level::where('start_points' ,'<=' ,$user->points)
    //     ->where('end_points' ,'>=' , $user->points)->first();

    //     $levelUser = LevelUser::where('user_id' , $user->id)->first();

    //         $currentLevel = Level::where('id' , $levelUser->id)->first();

    //         if($level->id !== $currentLevel->id){
    //           LevelUser::create([
    //             'user_id' => $user->id,
    //             'level_id' => $level->id
    //           ]);
    //         }

    //     event(new AddGiftEvent($user->id , $gift));

    //     $currentLevel = Level::where('id' , $levelUser->level_id )->first();

    //     $user->update([
    //         'points' => $user->points -  $gift->required_points * $request->quantity
    //     ]);
    //     // TODO add quantity in GiftUser table
    //     GiftUser::create([
    //         'user_id' => $user->id,
    //         'gift_id' => $gift->id,
    //         'quantity' => $request->quantity
    //     ]);
    //     if($level->id !== $currentLevel->id){
    //       LevelUser::create([
    //         'user_id' => $user->id,
    //         'level_id' => $level->id
    //       ]);
    //     }
    //     return response([
    //         'message' => 'gift add for user successfully'
    //     ]);
    // }


    public function addGiftForUser(Request $request, User $user)
{
    $request->validate([
        'gift_id' => ['required', Rule::exists('gifts', 'id')],
        'quantity' => 'required|numeric|min:1'
    ]);

    $gift = Gift::findOrFail($request->gift_id);
    $oldPoints = $user->points;

    UserPointHistory::create([
        'user_id' => $user->id,
        'points' => $user->points,
        'change' => $oldPoints - $user->points,
        'signal' => '-'
    ]);

    $level = Level::where('start_points', '<=', $user->points)
        ->where('end_points', '>=', $user->points)
        ->first();

    $levelUser = LevelUser::where('user_id', $user->id)->latest()->first();

    if ($level) {
        if ($level->id !== $levelUser->level_id) {
            LevelUser::create([
                'user_id' => $user->id,
                'level_id' => $level->id
            ]);
        }
    }

    event(new AddGiftEvent($user->id, $gift));

    $user->update([
        'points' => $user->points - $gift->required_points * $request->quantity
    ]);

    // TODO: add quantity in GiftUser table
    GiftUser::create([
        'user_id' => $user->id,
        'gift_id' => $gift->id,
        'quantity' => $request->quantity,
        'required_points' => $gift->required_points
    ]);

    return response([
        'message' => 'Gift added for user successfully'
    ]);
}
        public function getUserGifts(User $user) {
            $gift = $user->giftUser()->where('required_points', '<=' , $user->points)->with('gift')->get();
            return $gift;
        }

}
