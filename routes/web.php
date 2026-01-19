<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ReferralRedirectController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

// Admin Routes
Route::prefix('admin')->name('admin.')->group(function () {
    // Auth routes
    Route::get('/login', [App\Http\Controllers\Admin\AuthController::class, 'showLogin'])->name('login');
    Route::post('/login/otp', [App\Http\Controllers\Admin\AuthController::class, 'requestOtp'])->name('login.otp');
    Route::post('/login/verify', [App\Http\Controllers\Admin\AuthController::class, 'verifyOtp'])->name('login.verify');
    Route::post('/logout', [App\Http\Controllers\Admin\AuthController::class, 'logout'])->name('logout');

    // Admin routes (auth middleware disabled temporarily)
    Route::get('/dashboard', [App\Http\Controllers\Admin\AdminController::class, 'dashboard'])->name('dashboard');
    Route::get('/users', [App\Http\Controllers\Admin\AdminController::class, 'users'])->name('users');
    Route::get('/packages', [App\Http\Controllers\Admin\AdminController::class, 'packages'])->name('packages');
    Route::get('/transactions', [App\Http\Controllers\Admin\AdminController::class, 'transactions'])->name('transactions');
    Route::get('/withdrawals', [App\Http\Controllers\Admin\AdminController::class, 'withdrawals'])->name('withdrawals');
    Route::get('/rewards', [App\Http\Controllers\Admin\AdminController::class, 'rewards'])->name('rewards');
    Route::get('/reward-claims', [App\Http\Controllers\Admin\AdminController::class, 'rewardClaims'])->name('reward-claims');
    Route::get('/analytics', [App\Http\Controllers\Admin\AdminController::class, 'analytics'])->name('analytics');
    Route::get('/profile', [App\Http\Controllers\Admin\AdminController::class, 'profile'])->name('profile');

    // User management
    Route::post('/users/{id}/toggle-status', [App\Http\Controllers\Admin\AdminController::class, 'toggleUserStatus'])->name('users.toggle-status');
});



// Route::get('/r/{code}', [ReferralRedirectController::class, 'redirect'])
//     ->where('code', '[A-Za-z0-9_-]+')
//     ->middleware('throttle:referral');

// agent routes
Route::get('/agents', [App\Http\Controllers\AgentController::class, 'index']);
Route::get('/agents/{id}', [App\Http\Controllers\AgentController::class, 'show']);
Route::post('/agents', [App\Http\Controllers\AgentController::class, 'store']);
Route::post('/agents/{id}', [App\Http\Controllers\AgentController::class, 'update']);
Route::post('/agents/{id}/approve', [App\Http\Controllers\AgentController::class, 'approve']);
Route::post('/agents/{id}/reject', [App\Http\Controllers\AgentController::class, 'reject']);
Route::post('/agents/{id}/activate', [App\Http\Controllers\AgentController::class, 'activate']);
Route::post('/agents/{id}/deactivate', [App\Http\Controllers\AgentController::class, 'deactivate']);
Route::delete('/agents/{id}', [App\Http\Controllers\AgentController::class, 'destroy']);

// produk routes
Route::get('/produk', [App\Http\Controllers\ProdukController::class, 'index']);
Route::get('/produk/{id}', [App\Http\Controllers\ProdukController::class, 'show']);
Route::post('/produk', [App\Http\Controllers\ProdukController::class, 'store']);
Route::post('/produk/{id}', [App\Http\Controllers\ProdukController::class, 'update']);
Route::delete('/produk/{id}', [App\Http\Controllers\ProdukController::class, 'destroy']);

// affiliate routes
Route::get('/affiliates', [App\Http\Controllers\AffiliateController::class, 'index']);
Route::get('/affiliates/{id}', [App\Http\Controllers\AffiliateController::class, 'show']);
Route::post('/affiliates', [App\Http\Controllers\AffiliateController::class, 'store']);
Route::put('/affiliates/{id}', [App\Http\Controllers\AffiliateController::class, 'update']);
Route::delete('/affiliates/{id}', [App\Http\Controllers\AffiliateController::class, 'destroy']);
Route::post('/affiliates/{id}/activate', [App\Http\Controllers\AffiliateController::class, 'activate']);
Route::post('/affiliates/{id}/deactivate', [App\Http\Controllers\AffiliateController::class, 'deactivate']);
Route::get('/affiliates/{id}/agents', [App\Http\Controllers\AffiliateController::class, 'agents']);

// freelance routes
Route::get('/freelances', [App\Http\Controllers\FreelanceController::class, 'index']);
Route::get('/freelances/{id}', [App\Http\Controllers\FreelanceController::class, 'show']);
Route::post('/freelances', [App\Http\Controllers\FreelanceController::class, 'store']);
Route::put('/freelances/{id}', [App\Http\Controllers\FreelanceController::class, 'update']);
Route::delete('/freelances/{id}', [App\Http\Controllers\FreelanceController::class, 'destroy']);
Route::post('/freelances/{id}/activate', [App\Http\Controllers\FreelanceController::class, 'activate']);
Route::post('/freelances/{id}/deactivate', [App\Http\Controllers\FreelanceController::class, 'deactivate']);
Route::get('/freelances/{id}/agents', [App\Http\Controllers\FreelanceController::class, 'agents']);

// auth routes
Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
Route::get('/auth/google/url', [App\Http\Controllers\Api\AuthController::class, 'redirectToGoogle']);
Route::get('/auth/google/callback', [App\Http\Controllers\Api\AuthController::class, 'handleGoogleCallback']);
