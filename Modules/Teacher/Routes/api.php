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

Route::resource('teachers', TeacherController::class)->middleware(['auth:teacher']);

Route::prefix('teacher')->middleware(['auth:teacher'])->group(function () {
    Route::put('/', [TeacherController::class, 'index']);
    Route::get('/profile', [TeacherController::class, 'update']);
    Route::delete('/destroy', [TeacherController::class, 'destroy']);

    Route::get('/{courseId}/sections', [TeacherController::class, 'getSectionCreatedByTeacher']);
    Route::get('/{sectionId}/videos', [TeacherController::class, 'getVideoCreatedByTeacher']);
    Route::get('/{sectionId}/files', [TeacherController::class, 'getFilesCreatedByTeacher']);
});
