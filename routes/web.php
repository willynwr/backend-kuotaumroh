<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ReferralRedirectController;
use App\Http\Controllers\AgentController;
use App\Http\Controllers\DashboardController;

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
})->name('welcome');

// Auth Routes - Unified Login for all users (Agent, Affiliate, Freelance) 
// Agent login on /agent (before prefix group to avoid conflict)
Route::get('/agent', function () {
    return view('auth.login');
})->name('login');

// Agent Signup tanpa Referral (default affiliate_id = 1)
Route::get('/signup', [App\Http\Controllers\AgentController::class, 'signup'])->name('signup');

// Route khusus untuk agent pending (by ID, karena belum punya link_referal)
// HARUS SEBELUM /agent/{link_referral} untuk menghindari conflict
Route::get('/agent/pending', function(Request $request) {
    try {
        \Log::info('=== AGENT PENDING ROUTE ACCESSED ===');
        
        $agentId = $request->query('id');
        \Log::info('Agent ID from query: ' . $agentId);
        
        if (!$agentId) {
            \Log::warning('No agent ID provided');
            return redirect()->route('login')->with('error', 'ID agent tidak ditemukan');
        }
        
        $agent = \App\Models\Agent::find($agentId);
        \Log::info('Agent found: ' . ($agent ? 'YES' : 'NO'));
        
        if (!$agent) {
            \Log::warning('Agent not found with ID: ' . $agentId);
            return redirect()->route('login')->with('error', 'Akun tidak ditemukan');
        }
        
        \Log::info('Agent status: ' . $agent->status);
        
        if ($agent->status !== 'pending') {
            // Jika sudah approved, redirect ke dashboard normal
            if ($agent->link_referal) {
                \Log::info('Agent approved, redirecting to: /dash/' . $agent->link_referal);
                return redirect('/dash/' . $agent->link_referal);
            }
            \Log::warning('Agent approved but no link_referal');
            return redirect()->route('login')->with('error', 'Akun Anda sudah disetujui, silakan login ulang');
        }
        
        \Log::info('Rendering pending dashboard for agent ID: ' . $agentId);
        
        // Render dashboard khusus untuk agent pending
        return view('agent.pending.dashboard', [
            'user' => $agent,
            'linkReferral' => null,
            'portalType' => 'agent',
        ]);
    } catch (\Exception $e) {
        \Log::error('Error in agent pending route: ' . $e->getMessage());
        \Log::error('Stack trace: ' . $e->getTraceAsString());
        return redirect()->route('login')->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
    }
})->name('agent.pending');

// Agent Signup dengan Referral Link dari Affiliate/Freelance
Route::get('/agent/{link_referral}', [App\Http\Controllers\AgentController::class, 'signupWithReferral'])->name('agent.signup.referral');

// Halaman Toko Agent - /u/{link_referal}
Route::get('/u/{link_referal}', [App\Http\Controllers\AgentController::class, 'showStore'])->name('agent.store.view');

Route::get('/callback', function () {
    return view('auth.callback');
})->name('callback');

Route::get('/checkout', function () {
    return view('checkout');
})->name('checkout');

// Halaman Invoice
Route::get('/invoice', function () {
    return view('invoice');
})->name('invoice');

Route::get('/invoice/{id}', function ($id) {
    return view('invoice', ['invoiceId' => $id]);
})->name('invoice.show');

// Halaman Payment Umroh - /umroh/payment?id=xxx
Route::get('/umroh/payment', function () {
    return view('payment');
})->name('umroh.payment');

Route::get('/admin/login', function () {
    return view('auth.admin.login');
})->name('admin.login');
// Dashboard Unique Routes untuk Affiliate & Freelance & Agent
Route::prefix('dash')->name('dash.')->middleware('web')->group(function () {
    Route::get('/{link_referral}', [DashboardController::class, 'show'])->name('show');
    Route::get('/{link_referral}/downlines', [DashboardController::class, 'downlines'])->name('downlines');
    Route::post('/{link_referral}/downlines/agent', [DashboardController::class, 'storeDownlineAgent'])->name('downlines.store-agent');
    Route::get('/{link_referral}/rewards', [DashboardController::class, 'rewards'])->name('rewards');
    Route::get('/{link_referral}/points-history', [DashboardController::class, 'pointsHistory'])->name('points-history');
    Route::get('/{link_referral}/profile', [DashboardController::class, 'profile'])->name('profile');
    Route::get('/{link_referral}/invite', [DashboardController::class, 'invite'])->name('invite');
    
    // Agent specific routes
    Route::get('/{link_referral}/order', [DashboardController::class, 'order'])->name('order');
    Route::get('/{link_referral}/checkout', [DashboardController::class, 'checkout'])->name('checkout');
    Route::get('/{link_referral}/history', [DashboardController::class, 'history'])->name('history');
    Route::get('/{link_referral}/wallet', [DashboardController::class, 'wallet'])->name('wallet');
    Route::get('/{link_referral}/history-profit', [DashboardController::class, 'historyProfit'])->name('history-profit');
    Route::get('/{link_referral}/history-profit/{month}', [DashboardController::class, 'historyProfitDetail'])->name('history-profit.detail');
    Route::get('/{link_referral}/withdraw', [DashboardController::class, 'withdraw'])->name('withdraw');
    Route::get('/{link_referral}/referrals', [DashboardController::class, 'referrals'])->name('referrals');
    Route::get('/{link_referral}/catalog', [DashboardController::class, 'catalog'])->name('catalog');
});


// Route khusus untuk agent pending (by link_referal, untuk yang sudah punya)
Route::get('/agent/pending/{linkReferral}', function($linkReferral) {
    $agent = \App\Models\Agent::where('link_referal', $linkReferral)
        ->where('status', 'pending')
        ->first();
    
    if (!$agent) {
        return redirect()->route('login')->with('error', 'Akun tidak ditemukan');
    }
    
    return view('agent.pending.dashboard', [
        'user' => $agent,
        'linkReferral' => $linkReferral,
        'portalType' => 'agent',
    ]);
})->name('agent.pending.dashboard');

// API Routes untuk mendapatkan data dashboard
Route::prefix('api/dash')->name('api.dash.')->group(function () {
    Route::get('/affiliate/{link_referral}', [DashboardController::class, 'getAffiliateData'])->name('affiliate');
    Route::get('/freelance/{link_referral}', [DashboardController::class, 'getFreelanceData'])->name('freelance');
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
    
    // Margin routes
    Route::post('/margins', [App\Http\Controllers\Admin\AdminController::class, 'storeMargin'])->name('margins.store');
    Route::put('/margins/{id}', [App\Http\Controllers\Admin\AdminController::class, 'updateMargin'])->name('margins.update');
    Route::delete('/margins/{id}', [App\Http\Controllers\Admin\AdminController::class, 'deleteMargin'])->name('margins.delete');
    
    Route::get('/transactions', [App\Http\Controllers\Admin\AdminController::class, 'transactions'])->name('transactions');
    Route::get('/orders', [App\Http\Controllers\Admin\AdminController::class, 'orders'])->name('orders');
    Route::get('/withdrawals', [App\Http\Controllers\Admin\AdminController::class, 'withdrawals'])->name('withdrawals');
    Route::post('/withdrawals/{id}/approve', [App\Http\Controllers\Admin\AdminController::class, 'approveWithdrawal'])->name('withdrawals.approve');
    Route::post('/withdrawals/{id}/reject', [App\Http\Controllers\Admin\AdminController::class, 'rejectWithdrawal'])->name('withdrawals.reject');
    Route::get('/rewards', [App\Http\Controllers\Admin\AdminController::class, 'rewards'])->name('rewards');
    Route::get('/reward-claims', [App\Http\Controllers\Admin\AdminController::class, 'rewardClaims'])->name('reward-claims');
    Route::get('/analytics', [App\Http\Controllers\Admin\AdminController::class, 'analytics'])->name('analytics');
    Route::get('/profile', [App\Http\Controllers\Admin\AdminController::class, 'profile'])->name('profile');

    // User management
    Route::post('/users/{id}/toggle-status', [App\Http\Controllers\Admin\AdminController::class, 'toggleUserStatus'])->name('users.toggle-status');

    // Affiliate Management
    Route::prefix('affiliates')->name('affiliates.')->group(function () {
        Route::get('/', [App\Http\Controllers\Admin\AdminController::class, 'indexAffiliates'])->name('index');
        Route::get('/create', [App\Http\Controllers\Admin\AdminController::class, 'createAffiliate'])->name('create');
        Route::post('/', [App\Http\Controllers\Admin\AdminController::class, 'storeAffiliate'])->name('store');
        Route::get('/{id}', [App\Http\Controllers\Admin\AdminController::class, 'showAffiliate'])->name('show');
        Route::get('/{id}/edit', [App\Http\Controllers\Admin\AdminController::class, 'editAffiliate'])->name('edit');
        Route::put('/{id}', [App\Http\Controllers\Admin\AdminController::class, 'updateAffiliate'])->name('update');
        Route::delete('/{id}', [App\Http\Controllers\Admin\AdminController::class, 'destroyAffiliate'])->name('destroy');
        Route::post('/{id}/activate', [App\Http\Controllers\Admin\AdminController::class, 'activateAffiliate'])->name('activate');
        Route::post('/{id}/deactivate', [App\Http\Controllers\Admin\AdminController::class, 'deactivateAffiliate'])->name('deactivate');
    });

    // Freelance Management
    Route::prefix('freelances')->name('freelances.')->group(function () {
        Route::get('/', [App\Http\Controllers\Admin\AdminController::class, 'indexFreelances'])->name('index');
        Route::get('/create', [App\Http\Controllers\Admin\AdminController::class, 'createFreelance'])->name('create');
        Route::post('/', [App\Http\Controllers\Admin\AdminController::class, 'storeFreelance'])->name('store');
        Route::get('/{id}', [App\Http\Controllers\Admin\AdminController::class, 'showFreelance'])->name('show');
        Route::get('/{id}/edit', [App\Http\Controllers\Admin\AdminController::class, 'editFreelance'])->name('edit');
        Route::put('/{id}', [App\Http\Controllers\Admin\AdminController::class, 'updateFreelance'])->name('update');
        Route::delete('/{id}', [App\Http\Controllers\Admin\AdminController::class, 'destroyFreelance'])->name('destroy');
        Route::post('/{id}/activate', [App\Http\Controllers\Admin\AdminController::class, 'activateFreelance'])->name('activate');
        Route::post('/{id}/deactivate', [App\Http\Controllers\Admin\AdminController::class, 'deactivateFreelance'])->name('deactivate');
    });

    // Agent Management
    Route::prefix('agents')->name('agents.')->group(function () {
        Route::get('/', [App\Http\Controllers\Admin\AdminController::class, 'indexAgents'])->name('index');
        Route::get('/create', [App\Http\Controllers\Admin\AdminController::class, 'createAgent'])->name('create');
        Route::post('/', [App\Http\Controllers\Admin\AdminController::class, 'storeAgent'])->name('store');
        Route::get('/{id}', [App\Http\Controllers\Admin\AdminController::class, 'showAgent'])->name('show');
        Route::get('/{id}/edit', [App\Http\Controllers\Admin\AdminController::class, 'editAgent'])->name('edit');
        Route::put('/{id}', [App\Http\Controllers\Admin\AdminController::class, 'updateAgent'])->name('update');
        Route::delete('/{id}', [App\Http\Controllers\Admin\AdminController::class, 'destroyAgent'])->name('destroy');
        Route::post('/{id}/approve', [App\Http\Controllers\Admin\AdminController::class, 'approveAgent'])->name('approve');
        Route::post('/{id}/reject', [App\Http\Controllers\Admin\AdminController::class, 'rejectAgent'])->name('reject');
        Route::post('/{id}/activate', [App\Http\Controllers\Admin\AdminController::class, 'activateAgent'])->name('activate');
        Route::post('/{id}/deactivate', [App\Http\Controllers\Admin\AdminController::class, 'deactivateAgent'])->name('deactivate');
    });
});



Route::get('/r/{code}', [ReferralRedirectController::class, 'redirect'])
    ->where('code', '[A-Za-z0-9_-]+')
    ->middleware('throttle:referral');

Route::prefix('agent')->name('agent.')->group(function () {
    Route::get('/assets/{file}', [AgentController::class, 'asset'])
        ->where('file', '[A-Za-z0-9_\-\.]+')
        ->name('asset');
    Route::get('/dashboard', [AgentController::class, 'dashboard'])->name('dashboard');
    Route::get('/catalog', [AgentController::class, 'catalog'])->name('catalog');
    Route::get('/history', [AgentController::class, 'history'])->name('history');
    Route::get('/orders', [AgentController::class, 'history'])->name('orders');
    Route::get('/order', [AgentController::class, 'order'])->name('order');
    Route::get('/checkout', [AgentController::class, 'checkout'])->name('checkout');
    Route::get('/wallet', [AgentController::class, 'wallet'])->name('wallet');
    Route::get('/history-profit', [AgentController::class, 'historyProfit'])->name('history-profit');
    Route::get('/profile', [AgentController::class, 'profile'])->name('profile');
    Route::get('/referrals', [AgentController::class, 'referrals'])->name('referrals');
    
    // Withdraw routes
    Route::post('/withdraws', [App\Http\Controllers\Agent\WithdrawController::class, 'store'])->name('withdraws.store');
    Route::get('/withdraws', [App\Http\Controllers\Agent\WithdrawController::class, 'index'])->name('withdraws.index');
    Route::post('/withdraws/{id}/approve', [App\Http\Controllers\Agent\WithdrawController::class, 'approve'])->name('withdraws.approve');
    Route::post('/withdraws/{id}/reject', [App\Http\Controllers\Agent\WithdrawController::class, 'reject'])->name('withdraws.reject');
    
    // Rekening routes
    Route::get('/rekenings', [App\Http\Controllers\Agent\RekeningController::class, 'index'])->name('rekenings.index');
    Route::post('/rekenings', [App\Http\Controllers\Agent\RekeningController::class, 'store'])->name('rekenings.store');
    Route::delete('/rekenings/{id}', [App\Http\Controllers\Agent\RekeningController::class, 'destroy'])->name('rekenings.destroy');
});

// Agent Signup dengan Referral Link dari Affiliate/Freelance (Must be after agent.* routes)
Route::get('/agent/{link_referral}', [App\Http\Controllers\AgentController::class, 'signupWithReferral'])->name('agent.signup.referral');

// agent routes
Route::get('/agents', [App\Http\Controllers\AgentController::class, 'index']);
Route::get('/agents/{id}', [App\Http\Controllers\AgentController::class, 'show']);
Route::post('/agents', [App\Http\Controllers\AgentController::class, 'store'])->name('agent.store');
Route::post('/agents/{id}', [App\Http\Controllers\AgentController::class, 'update']);
Route::post('/agents/{id}/approve', [App\Http\Controllers\AgentController::class, 'approve']);
Route::post('/agents/{id}/reject', [App\Http\Controllers\AgentController::class, 'reject']);
Route::post('/agents/{id}/activate', [App\Http\Controllers\AgentController::class, 'activate']);
Route::post('/agents/{id}/deactivate', [App\Http\Controllers\AgentController::class, 'deactivate']);
Route::delete('/agents/{id}', [App\Http\Controllers\AgentController::class, 'destroy']);

// produk routes
Route::get('/produk', [App\Http\Controllers\ProdukController::class, 'index']);
Route::get('/produk/{id}', [App\Http\Controllers\ProdukController::class, 'show']);
Route::get('/packages/by-provider/{provider}', [App\Http\Controllers\ProdukController::class, 'getByProvider']);
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

// affiliate portal routes
Route::prefix('affiliate')->group(function () {
    Route::get('dashboard', [DashboardController::class, 'affiliateDashboard'])->name('affiliate.dashboard');
    Route::get('downlines', function () {
        return view('affiliate.downlines');
    })->name('affiliate.downlines');
    Route::get('orders', function () {
        return view('affiliate.history');
    })->name('affiliate.orders');
    Route::get('order', function () {
        return view('affiliate.order');
    })->name('affiliate.order');
    Route::get('checkout', function () {
        return view('affiliate.checkout');
    })->name('affiliate.checkout');
    Route::get('invite', function () {
        return view('affiliate.invite');
    })->name('affiliate.invite');
    Route::get('points-history', function () {
        return view('affiliate.points-history');
    })->name('affiliate.points-history');
    Route::get('profile', function () {
        return view('affiliate.profile');
    })->name('affiliate.profile');
    Route::get('rewards', function () {
        return view('affiliate.rewards');
    })->name('affiliate.rewards');
});

// Order page for affiliate and freelance (uses same route pattern as dashboard)
Route::get('/dash/{linkReferral}/order', [DashboardController::class, 'order'])->name('dash.order');

// freelance routes
Route::prefix('freelance')->group(function () {
    Route::get('dashboard', [DashboardController::class, 'freelanceDashboard'])->name('freelance.dashboard');
    Route::get('downlines', function () {
        return view('freelance.downlines');
    })->name('freelance.downlines');
    Route::get('orders', function () {
        return view('freelance.history');
    })->name('freelance.orders');
    Route::get('order', function () {
        return view('freelance.order');
    })->name('freelance.order');
    Route::get('checkout', function () {
        return view('freelance.checkout');
    })->name('freelance.checkout');
    Route::get('invite', function () {
        return view('freelance.invite');
    })->name('freelance.invite');
    Route::get('points-history', function () {
        return view('freelance.points-history');
    })->name('freelance.points-history');
    Route::get('profile', function () {
        return view('freelance.profile');
    })->name('freelance.profile');
    Route::get('rewards', function () {
        return view('freelance.rewards');
    })->name('freelance.rewards');
});

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
