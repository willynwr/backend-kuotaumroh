<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AgentController;
use App\Http\Controllers\AffiliateController;
use App\Http\Controllers\FreelanceController;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\UmrohPaymentController;
use App\Http\Controllers\Api\ProxyController;

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

// Google OAuth Routes - need web middleware for session
Route::middleware('web')->group(function () {
    Route::get('/auth/google/url', [AuthController::class, 'getGoogleAuthUrl']);
    Route::get('/auth/google/callback', [AuthController::class, 'handleGoogleCallback']);
    Route::post('/auth/session', [App\Http\Controllers\Api\AuthController::class, 'saveSession']);
    Route::post('/auth/logout', [App\Http\Controllers\Api\AuthController::class, 'destroySession']);
});

// Admin API Routes (protected with Sanctum)
Route::middleware('auth:sanctum')->prefix('admin')->group(function () {
    Route::get('/me', [App\Http\Controllers\Api\AdminController::class, 'getCurrentAdmin']);
    Route::post('/logout', [App\Http\Controllers\Api\AdminController::class, 'logout']);
});

/*
|--------------------------------------------------------------------------
| Proxy API Routes (untuk menghindari CORS)
|--------------------------------------------------------------------------
|
| Semua request dari frontend akan diteruskan ke external API
| Contoh: /api/proxy/umroh/package → tokodigi.id/api/umroh/package
|
*/
Route::prefix('proxy')->group(function () {
    // Catch all GET requests
    Route::get('/{path}', [ProxyController::class, 'proxyGet'])->where('path', '.*');
    
    // Catch all POST requests
    Route::post('/{path}', [ProxyController::class, 'proxyPost'])->where('path', '.*');
    
    // Catch all PUT requests
    Route::put('/{path}', [ProxyController::class, 'proxyPut'])->where('path', '.*');
    
    // Catch all DELETE requests
    Route::delete('/{path}', [ProxyController::class, 'proxyDelete'])->where('path', '.*');
});

/*
|--------------------------------------------------------------------------
| Umroh Payment API Routes
|--------------------------------------------------------------------------
|
| API endpoints untuk sistem pembayaran paket Umroh
|
*/
Route::prefix('umroh')->group(function () {
    // 1. GET Catalog Paket
    // GET /api/umroh/package?ref_code=bulk_umroh
    Route::get('/package', [UmrohPaymentController::class, 'getPackages']);

    // 2. POST Order Bulk Payment
    // POST /api/umroh/bulkpayment
    Route::post('/bulkpayment', [UmrohPaymentController::class, 'createBulkPayment']);
    
    // 3. POST Order Individual Payment (untuk homepage / public user)
    // POST /api/umroh/payment
    Route::post('/payment', [UmrohPaymentController::class, 'createIndividualPayment']);

    // 4. GET Order Bulk History
    // GET /api/umroh/bulkpayment?agent_id=600001
    Route::get('/bulkpayment', [UmrohPaymentController::class, 'getHistory']);

    // 5. GET Detail MSISDN Bulk Order
    // GET /api/umroh/bulkpayment/detail?id=123456789&agent_id=600001
    Route::get('/bulkpayment/detail', [UmrohPaymentController::class, 'getDetail']);

    // Payment Status & Verification
    // GET /api/umroh/payment/status?id=123
    Route::get('/payment/status', [UmrohPaymentController::class, 'getPaymentStatus']);

    // GET /api/umroh/payment/local-detail?id=123
    // Get payment detail from local database (with updated status)
    Route::get('/payment/local-detail', [UmrohPaymentController::class, 'getLocalDetail']);

    // POST /api/umroh/payment/verify
    Route::post('/payment/verify', [UmrohPaymentController::class, 'verifyPayment']);

    // Payment Callback (from QRIS provider)
    // POST /api/umroh/payment/callback
    Route::post('/payment/callback', [UmrohPaymentController::class, 'paymentCallback']);
});

/*
|--------------------------------------------------------------------------
| Payment Tracking Routes
|--------------------------------------------------------------------------
|
| Public routes untuk tracking pembayaran dengan external_payment_id
|
*/
Route::get('/pembayaran/{external_payment_id}/status', [UmrohPaymentController::class, 'getPaymentStatusByExternalId']);

/*
|--------------------------------------------------------------------------
| Agent Dashboard API Routes
|--------------------------------------------------------------------------
|
| API endpoints untuk statistik dan data dashboard agent
|
*/
Route::prefix('agent')->group(function () {
    // GET /api/agent/stats?agent_id=1
    Route::get('/stats', [App\Http\Controllers\Api\AgentStatsController::class, 'getStats']);
});
