<?php

use Illuminate\Support\Facades\Route;
use Modules\Auth\Http\Controllers\OtpController;
use Modules\Auth\Http\Controllers\RegisterController;
use Modules\Auth\Http\Controllers\ResetPasswordController;
use Modules\Auth\Http\Controllers\SessionController;

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

Route::middleware('guest')->group(function () {
    Route::post('login', SessionController::class);
    Route::post('register', RegisterController::class);
});
Route::post('verify-otp', [OtpController::class, 'verify'])->middleware('auth:teacher,student');
Route::post('resend-otp', [OtpController::class, 'resendOtp'])->middleware('auth:teacher,student');

Route::post('teacher/logout', [SessionController::class, 'destroy'])->middleware(['auth:teacher', 'verified']);
Route::post('student/logout', [SessionController::class, 'destroy'])->middleware(['auth:student', 'verified']);

Route::prefix('password')->group(function () {
    Route::post('verification', [ResetPasswordController::class, 'resetLinkEmail']);
    Route::post('reset', [ResetPasswordController::class, 'resetPassword'])->middleware('verified');
});
