<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AgentController;
use App\Http\Controllers\AffiliateController;
use App\Http\Controllers\FreelanceController;
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

// Public JSON APIs for CRUD collections used by frontend pages
// Agents
Route::get('/agents', [AgentController::class, 'index']);
Route::get('/agents/{id}', [AgentController::class, 'show']);
Route::post('/agents', [AgentController::class, 'store']);
Route::post('/agents/{id}', [AgentController::class, 'update']);
Route::post('/agents/{id}/approve', [AgentController::class, 'approve']);
Route::post('/agents/{id}/reject', [AgentController::class, 'reject']);
Route::post('/agents/{id}/activate', [AgentController::class, 'activate']);
Route::post('/agents/{id}/deactivate', [AgentController::class, 'deactivate']);
Route::delete('/agents/{id}', [AgentController::class, 'destroy']);

// Affiliates
Route::get('/affiliates', [AffiliateController::class, 'index']);
Route::get('/affiliates/{id}', [AffiliateController::class, 'show']);
Route::post('/affiliates', [AffiliateController::class, 'store']);
Route::put('/affiliates/{id}', [AffiliateController::class, 'update']);
Route::delete('/affiliates/{id}', [AffiliateController::class, 'destroy']);
Route::post('/affiliates/{id}/activate', [AffiliateController::class, 'activate']);
Route::post('/affiliates/{id}/deactivate', [AffiliateController::class, 'deactivate']);
Route::get('/affiliates/{id}/agents', [AffiliateController::class, 'agents']);

// Freelances
Route::get('/freelances', [FreelanceController::class, 'index']);
Route::get('/freelances/{id}', [FreelanceController::class, 'show']);
Route::post('/freelances', [FreelanceController::class, 'store']);
Route::put('/freelances/{id}', [FreelanceController::class, 'update']);
Route::delete('/freelances/{id}', [FreelanceController::class, 'destroy']);
Route::post('/freelances/{id}/activate', [FreelanceController::class, 'activate']);
Route::post('/freelances/{id}/deactivate', [FreelanceController::class, 'deactivate']);
