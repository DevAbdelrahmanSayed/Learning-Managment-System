<?php

use Illuminate\Support\Facades\Route;
use Modules\Course\Http\Controllers\CourseController;

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

Route::apiResource('courses', CourseController::class); //->middleware(['auth:teacher', 'Verify:teacher']);
Route::get('teacher/courses', [CourseController::class, 'getCoursesCreatedByTeacher'])->middleware(['auth:teacher', 'Verify:teacher']);
