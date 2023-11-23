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
use App\Services\Notifications\NotificationService;
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
        }else{
            $latestLevel = Level::latest()->first();
            LevelUser::create([
                'user_id' => $user->id,
                'level_id' => $latestLevel->id
            ]);
        }
        $device_key = User::where('id', $user->id)->pluck('device_key')->first();
        $content = "New Points Added To Your Account" . $points;
        $type = "points";
        NotificationService::sendNotification($device_key, $content, $type, $points);
        return UserResource::make($user);

    }

    public function addGiftForUser(Request $request, User $user) {
        $gift = Gift::findOrFail($request->gift_id);
        if($user->points >= $gift->required_points) {
            $request->validate([
                'gift_id' => ['required', Rule::exists('gifts', 'id')],
                'quantity' => 'required|numeric|min:1'
            ]);

            $oldPoints = $user->points;
            $newPoints = $user->points - $gift->required_points;
            UserPointHistory::create([
                'user_id' => $user->id,
                'points' => $newPoints,
                'change' => $oldPoints - $newPoints,
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

          //  event(new AddGiftEvent($user->id, $gift));

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
            $device_key = User::where('id', $user->id)->pluck('device_key')->first();
            $content = "New Gift Added To Your Account" . $gift->name;
            $type = "gift";
            NotificationService::sendNotification($device_key, $content, $type,$gift->required_points);
          //  event(new AddGiftEvent($user->id, $gift));

            return response([
                'message' => 'Gift added for user successfully'
            ]);
        } else {
                return response()->json([
                   'You Cannot Buy this gift' , 201
                ]);
        }
    }

        public function getUserGifts(User $user) {
            $gift = Gift::whereHas('giftUser' , fn($query) =>
            $query->where('user_id' , $user->id)
                ->where('required_points', '<=' , $user->points)
            )->get();
            return $gift;
        }
}
