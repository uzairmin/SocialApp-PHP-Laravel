<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PostController;
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

// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();});

Route::group(['middleware' => "tokenAuth"], function()
{
    Route::post('/post',[PostController::class,'posting'])->middleware("postAuth");
    Route::post('/show_post',[ShowController::class,'showPost']);
    Route::post('/delete_post',[PostController::class,'deletePost']);
    Route::post('/update_post_file',[PostController::class,'updateFile']);
    Route::post('/update_post_access',[PostController::class,'updateAccess']);
});
