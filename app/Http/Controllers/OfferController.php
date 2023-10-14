<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreOfferRequest;
use App\Http\Requests\UpdateOfferRequest;
use App\Http\Resources\OfferResource;
use App\Models\Offer;
use Illuminate\Http\Request;

class OfferController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(){
        $offers = Offer::all();
        return OfferResource::collection($offers);
    }

    public function show(Offer $offer){
        return OfferResource::make($offer);
    }

    public function store(StoreOfferRequest $request){
     //   $request->validated($request->all());
        $offer = Offer::create($request->all());
        return OfferResource::make($offer);
    }

    public function update(UpdateOfferRequest $request , Offer $offer){
        $offer->update($request->all());
        return OfferResource::make($offer);
    }

    public function destroy(Offer $offer){
        $offer->delete();
        return response()->json(['message' => 'Gift Has Been Deleted Successfully']);
    }
}
