<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreLevelRequest;
use App\Http\Requests\UpdateLevelRequest;
use App\Http\Requests\UpdateUserRequest;
use App\Http\Resources\LevelResource;
use App\Models\Level;
use Illuminate\Http\Request;

class LevelController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(){
        $levels = Level::all();
        return LevelResource::collection($levels);
    }

    public function show(Level $level){
        return LevelResource::make($level);
    }

    public function store(StoreLevelRequest $request){
        $request->validated($request->all());

        $level = Level::create($request->all());

        return LevelResource::make($level);
    }

    public function update(UpdateLevelRequest $request , Level $level){
        $level->update($request->all());
        return LevelResource::make($level);
    }

    public function destroy(Level $level){
        $level->delete();
        return response()->json(['message' => 'Level Has Been Deleted Successfully']);
    }
}
