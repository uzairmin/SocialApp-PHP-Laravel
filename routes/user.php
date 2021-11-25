<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SignupController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\LogoutController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\FriendController;
use App\Http\Controllers\ShowController;
use App\Http\Controllers\ConfirmationController;

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

Route::post('/signup',[SignupController::class,'signingUp']);
Route::get('/confirmation/{email}/{token}',[ConfirmationController::class,'confirming']);
Route::post('/login',[LoginController::class,'loggingIn']);

// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();});

Route::group(['middleware' => "tokenAuth"], function()
{
    Route::post('/new_name',[LoginController::class,'updateName']);
    Route::post('/new_password',[LoginController::class,'updatePassword']);
    Route::post('/update_gender',[LoginController::class,'updateGender']);
    Route::post('/show_user',[ShowController::class,'showUser']);
    Route::post('/user_deactivate',[SignupController::class,'deactivate']);
    Route::post('/logout',[LogoutController::class,'loggingOut']);
});
