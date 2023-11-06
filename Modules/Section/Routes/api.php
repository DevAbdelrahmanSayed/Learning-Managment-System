<?php

use Illuminate\Support\Facades\Route;
use Modules\Section\Http\Controllers\SectionController;

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

Route::apiResource('courses/sections', SectionController::class)->except('index')->middleware(['auth:teacher', 'verified']);
Route::get('courses/{course}/sections', [SectionController::class, 'getSection'])->middleware(['auth:teacher', 'verified']);
