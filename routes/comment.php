<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CommentController;
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
    Route::post('/show_comments',[ShowController::class,'showComments']);
    Route::post('/comment',[CommentController::class,'commenting'])->middleware("commentAuth");
    Route::post('/delete_comment',[CommentController::class,'deleteComment']);
    Route::post('/update_comment_file',[CommentController::class,'updateFile']);
    Route::post('/update_comment_comment',[CommentController::class,'updateComment']);
});
