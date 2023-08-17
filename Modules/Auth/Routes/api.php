<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Modules\Auth\Http\Controllers\OtpController;
use Modules\Auth\Http\Controllers\SessionController;
use Modules\Auth\Http\Controllers\RegisterController;

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
Route::post('verify-otp', [OtpController::class,'otpVerify'])->middleware('auth:teacher');
Route::post('resend-otp', [OtpController::class, 'resendOtp'])->middleware('auth:teacher');
Route::post('teacher/logout', [SessionController::class , 'destroy'])->middleware(['auth:teacher','verified']);
Route::post('student/logout', [SessionController::class, 'destroy'])->middleware('auth:student');
