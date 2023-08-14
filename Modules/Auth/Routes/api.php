<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Modules\Auth\Http\Controllers\AuthController;
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


// Route::middleware('guest')->group(function () {
//     Route::post('login', [AuthController::class, 'login']);
//     Route::post('register', [AuthController::class, 'register']);
//     Route::post('refresh', [AuthController::class, 'refresh']);
// });

Route::middleware('guest')->group(function () {
    Route::post('login', SessionController::class);
    Route::post('register', RegisterController::class);
});

Route::post('logout', [AuthController::class , 'logout'])->middleware('auth');

