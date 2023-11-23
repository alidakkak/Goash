<?php

namespace App\Http\Controllers;

use App\Http\Requests\LoginUserRequest;
use App\Http\Requests\StoreUserRequest;
use App\Http\Resources\UserResource;
use App\Models\Level;
use App\Models\LevelUser;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function register(StoreUserRequest $request){
        $validatedData = $request->validated();
        if (!$validatedData) {
            return response(['error' => 'Validation failed'], 422);
        }

        DB::beginTransaction();

        try {
            $user = User::create($validatedData);

            LevelUser::create([
                'user_id' => $user->id,
                'level_id' => Level::first()->id
            ]);

            $token = $user->createToken('auth_token')->plainTextToken;

            DB::commit();

            return response([
                'user' => $user,
                'token' => $token
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response(['error' => 'An error occurred while registering'], 500);
        }
    }


    public function login(LoginUserRequest $request){
        $request->validated($request->all());

        $user = User::where('email', $request->email)->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            return response()->json(['message' => 'Wrong Password credentials'], 401);
        }

        $token = $user->createToken('auth_token')->plainTextToken;
        $user = User::find($user->id);
        $user->update([
            'device_key' => $request->device_key
        ]);

        return response([
            'user' => $user,
            'token' => $token
        ]);
    }

    public function logout(){
        $user = User::find(auth()->user()->id);
        $user->update([
            'device_key' => null
        ]);
        Auth::user()->currentAccessToken()->delete();
        return response()->json(['message' => 'User Logged out Successfully']);
    }
}
