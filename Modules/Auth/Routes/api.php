<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Modules\Auth\Http\Controllers\OtpController;
use Modules\Auth\Http\Controllers\SessionController;
use Modules\Auth\Http\Controllers\RegisterController;
use Modules\Auth\Http\Controllers\ResetPasswordController;

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
Route::post('verify-otp', [OtpController::class,'verify'])->middleware('auth:teacher');
Route::post('resend-otp', [OtpController::class, 'resendOtp'])->middleware('auth:teacher');
Route::post('teacher/logout', [SessionController::class , 'destroy'])->middleware(['auth:teacher','Verify:teacher']);
Route::post('student/logout', [SessionController::class, 'destroy'])->middleware('auth:student');

Route::prefix('password')->group(function () {
    // Reset link email route
    Route::post('verification', [ResetPasswordController::class, 'resetLinkEmail']);
    // Reset password route
    Route::post('reset', [ResetPasswordController::class, 'resetPassword'])->middleware('Verify:teacher');
});
