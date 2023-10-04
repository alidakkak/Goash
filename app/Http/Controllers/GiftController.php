<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreGiftRequest;
use App\Http\Requests\UpdateGiftRequest;
use App\Http\Resources\GiftResource;
use App\Http\Resources\GiftUserResource;
use App\Models\Gift;
use App\Models\User;
use App\Models\GiftUser;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class GiftController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(){
        $gifts = Gift::all();
        return GiftResource::collection($gifts);
    }

    // public function indexuser() {
    //     $gift = Gift::where(Auth::user()->id)
    // }

    public function show(Gift $gift){
        return GiftResource::make($gift);
    }

    public function store(StoreGiftRequest $request){
        $request->validated($request->all());
        $gift = Gift::create($request->all());

        // foreach($request->user_ids as $user_id) {
        //     GiftUser::create([
        //         'gift_id' => $gift->id,
        //         'user_id' => $user_id,
        //     ]);
        // }

        return GiftResource::make($gift);
    }

    public function update(UpdateGiftRequest $request , Gift $gift){
        $gift->update($request->all());
        return GiftResource::make($gift);
    }

    public function destroy(Gift $gift){
        $gift->delete();
        return response()->json(['message' => 'Gift Has Been Deleted Successfully']);
    }


    public function rateGift(Request $request , GiftUser $userGift){
        $giftUser = new GiftUser();
        $currentRating = $request->input('rating');
       // $user_id = $request->input('user_id');
    
        if ($currentRating > 5) {
            return response()->json(['message' => 'Rating should not exceed 5'], 400);
        }
        $userGift ->update([
            //'user_id' => $user_id,
           // 'gift_id' => $gift_id,
            'rating' => $currentRating,
        ]);

        $averageRating = GiftUser::where('gift_id', $userGift->gift_id)->avg('rating');
        $gift = Gift::findOrFail($userGift->gift_id);
        $gift->update([
            'average_rating' => $averageRating
        ]);
    
        return response()->json(['message' => 'Rating Successfully']);
    }


    public function userNotRate(Request $request) {
        $user_id = $request->input('user_id');
    
        // $gifts = Gift::whereDoesntHave('giftUser', function ($query) use ($user_id) {
        //     $query->where('user_id', $user_id);
        // })->get();
    
        // return response()->json(['gifts' => $gifts]);

        $giftUser = GiftUser::where('user_id' , $user_id)->where('rating' , 0)->get();

        return GiftUserResource::collection($giftUser);
    }
    
    
       
}