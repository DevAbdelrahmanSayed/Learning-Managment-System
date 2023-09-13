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

Route::prefix('teacher')->middleware(['auth:teacher'])->group(function () {
    Route::put('/', [TeacherController::class, 'update']);
    Route::get('/profile', [TeacherController::class, 'index']);
    Route::delete('/{id}', [TeacherController::class, 'destroy']);
    Route::get('/courses', [TeacherController::class, 'getCoursesCreatedByTeacher']);
    Route::get('/{courseId}/sections', [TeacherController::class, 'getSectionCreatedByTeacher']);
    Route::get('/{sectionId}/videos', [TeacherController::class, 'getVideoCreatedByTeacher']);
    Route::get('/{sectionId}/files', [TeacherController::class, 'getFilesCreatedByTeacher']);
});
