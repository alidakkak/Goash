<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreGiftRequest;
use App\Http\Requests\UpdateGiftRequest;
use App\Http\Resources\GiftResource;
use App\Models\Gift;
use Illuminate\Http\Request;

class GiftController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(){
        $gifts = Gift::all();
        return GiftResource::collection($gifts);
    }

    public function show(Gift $gift){
        return GiftResource::make($gift);
    }

    public function store(StoreGiftRequest $request){
        $request->validated($request->all());

        $level = Gift::create($request->all());

        return GiftResource::make($level);
    }

    public function update(UpdateGiftRequest $request , Gift $gift){
        $gift->update($request->all());
        return GiftResource::make($gift);
    }

    public function destroy(Gift $gift){
        $gift->delete();
        return response()->json(['message' => 'Gift Has Been Deleted Successfully']);
    }
}
