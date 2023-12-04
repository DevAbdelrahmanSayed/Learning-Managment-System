<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Modules\FavouriteCourse\Http\Controllers\FavouriteCourseController;

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

Route::prefix('v1')->group(function(){
    Route::apiResource('favourite-courses' , FavouriteCourseController::class)->middleware('auth:student');
});
