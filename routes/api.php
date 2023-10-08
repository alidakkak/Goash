<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\FeatureController;
use App\Http\Controllers\GiftController;
use App\Http\Controllers\LevelController;
use App\Http\Controllers\OfferController;
use App\Http\Controllers\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::post('/signup' , [AuthController::class , 'register']);
Route::post('/login' , [AuthController::class , 'login']);

Route::middleware(['auth:sanctum'])->group(function () {

    Route::post('/logout' , [AuthController::class , 'logout']);

    Route::get('/profile' , [UserController::class , 'profile']);

    Route::get('/users' , [UserController::class , 'index']);
    Route::post('/users' , [UserController::class , 'store']);
    Route::get('/users/{user}' , [UserController::class , 'show']);
    Route::patch('/users/{user}' , [UserController::class , 'update']);
    Route::post('/users/{user}/updateImage' , [UserController::class , 'updateImage']);
    Route::delete('/users/{user}' , [UserController::class , 'destroy']);
    Route::patch('/addPointForUser/{user}' , [UserController::class , 'addPointForUser']);
    Route::post('/addGiftForUser/{user}' , [UserController::class , 'addGiftForUser']);
    Route::get('/getUserGifts/{user}' , [UserController::class , 'getUserGifts']);


    Route::get('/levels' , [LevelController::class , 'index']);
    Route::get('/getLevelsWithfeatures' , [LevelController::class , 'getLevelsWithFeatures']);
    Route::post('/levels' , [LevelController::class , 'store']);
    Route::get('/levels/{level}' , [LevelController::class , 'show']);
    Route::post('/levels/{level}' , [LevelController::class , 'update']);
    Route::delete('/levels/{level}' , [LevelController::class , 'destroy']);


    Route::get('/gifts' , [GiftController::class , 'index']);
    Route::post('/gifts' , [GiftController::class , 'store']);
    Route::get('/gifts/{gift}' , [GiftController::class , 'show']);
    Route::post('/gifts/{gift}' , [GiftController::class , 'update']);
    Route::delete('/gifts/{gift}' , [GiftController::class , 'destroy']);
    Route::post('/rate-gift/{userGift}', [GiftController::class , 'rateGift']);
    Route::post('/user-rated-gifts', [GiftController::class, 'userNotRate']);



    Route::get('/offers' , [OfferController::class , 'index']);
    Route::post('/offers' , [OfferController::class , 'store']);
    Route::get('/offers/{offer}' , [OfferController::class , 'show']);
    Route::post('/offers/{offer}' , [OfferController::class , 'update']);
    Route::delete('/offers/{offer}' , [OfferController::class , 'destroy']);


    Route::get('/features' , [FeatureController::class , 'index']);
    Route::post('/features' , [FeatureController::class , 'store']);
    Route::get('/features/{feature}' , [FeatureController::class , 'show']);
    Route::post('/features/{feature}' , [FeatureController::class , 'update']);
    Route::delete('/features/{feature}' , [FeatureController::class , 'destroy']);


        Route::get('gethistory', [\App\Http\Controllers\HistoryController::class, 'get']);

});
