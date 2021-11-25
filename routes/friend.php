<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\FriendController;
use App\Http\Controllers\ShowController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::group(['middleware' => "tokenAuth"], function()
{
    
    Route::post('/show_friends',[ShowController::class,'showFriends']);
    Route::post('/add_friend',[FriendController::class,'addFriend'])->middleware("friendAuth");
    Route::post('/delete_friend',[FriendController::class,'unfriend']);
});
