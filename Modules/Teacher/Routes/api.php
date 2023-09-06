<?php

use Illuminate\Support\Facades\Route;
use Modules\Teacher\Http\Controllers\TeacherController;

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

Route::middleware(['auth:teacher'])->group(function () {
    Route::put('teacher', [TeacherController::class, 'update']);
    Route::delete('teacher/{id}', [TeacherController::class, 'destroy']);
    Route::get('teacher/{id}/courses', [TeacherController::class, 'getCoursesCreatedByTeacher']);
    Route::get('teacher/{courseId}/sections', [TeacherController::class, 'getSectionCreatedByTeacher']);
});
