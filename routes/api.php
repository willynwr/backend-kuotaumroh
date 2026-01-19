<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;

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

// Google OAuth Routes
Route::get('/auth/google/url', [AuthController::class, 'getGoogleAuthUrl']);
Route::get('/auth/google/callback', [AuthController::class, 'handleGoogleCallback']);

// Session Management - needs web middleware for session support
Route::middleware('web')->group(function () {
    Route::post('/auth/session', [App\Http\Controllers\Api\AuthController::class, 'saveSession']);
    Route::post('/auth/logout', [App\Http\Controllers\Api\AuthController::class, 'destroySession']);
});
