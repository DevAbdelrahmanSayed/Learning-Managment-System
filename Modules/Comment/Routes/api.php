<?php

use Illuminate\Http\Request;
use Modules\Comment\Http\Controllers\CommentController;

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


Route::post('course/comment', [CommentController::class, 'store'])->middleware(['auth:teacher']);
