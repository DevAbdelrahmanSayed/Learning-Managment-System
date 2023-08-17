<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Modules\Course\Http\Controllers\CourseController;
use Modules\Course\Http\Controllers\CoursexController;

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


Route::apiResource('courses', CourseController::class)->middleware(['auth:teacher','verified']);

