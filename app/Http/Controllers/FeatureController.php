<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreFeatureRequest;
use App\Http\Requests\UpdateFeatureRequest;
use App\Http\Resources\FeatureResource;
use App\Models\Feature;
use Illuminate\Http\Request;

class FeatureController extends Controller
{
    public function index(){
        $offers = Feature::all();
        return FeatureResource::collection($offers);
    }

    public function show(Feature $feature){
        return FeatureResource::make($feature);
    }

    public function store(StoreFeatureRequest $request){
        $request->validated($request->all());

        $feature = Feature::create($request->all());

        return FeatureResource::make($feature);
    }

    public function update(UpdateFeatureRequest $request , Feature $feature){
        $feature->update($request->all());
        return FeatureResource::make($feature);
    }

    public function destroy(Feature $feature){
        $feature->delete();
        return response()->json(['message' => 'Gift Has Been Deleted Successfully']);
    }
}
