<?php

use Illuminate\Support\Facades\Route;
use Modules\File\Http\Controllers\FileController;

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

Route::apiResource('courses/{section}/files', FileController::class)->middleware(['auth:teacher', 'Verify:teacher']);
