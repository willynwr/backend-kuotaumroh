<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Affiliate;
use App\Models\Freelance;
use App\Models\Agent;

class DashboardController extends Controller
{
    /**
     * Create a new controller instance.
     */
    public function __construct()
    {
        // Custom middleware to check session authentication
        $this->middleware(function ($request, $next) {
            $user = session('user');
            
            // Log for debugging
            \Log::info('Dashboard Middleware Check', [
                'session_id' => session()->getId(),
                'has_user' => !empty($user),
                'user_data' => $user,
                'path' => $request->path()
            ]);
            
            // If no session user, redirect to login
            if (!$user) {
                \Log::warning('No session user found, redirecting to login');
                return redirect()->route('login')->with('error', 'Silakan login terlebih dahulu');
            }
            
            return $next($request);
        });
    }

    /**
     * Extract provider name from package_id
     * Example: R1-TSEL-000 -> TELKOMSEL
     */
    private function extractProviderFromPackageId($packageId)
    {
        if (empty($packageId)) {
            return '-';
        }

        $providerMap = [
            'TSEL' => 'TELKOMSEL',
            'ISAT' => 'INDOSAT',
            'XL' => 'XL AXIATA',
            'TRI' => 'TRI',
            'AXIS' => 'AXIS',
            'SFREN' => 'SMARTFREN',
            'BYU' => 'BY.U',
        ];

        // Extract provider code from package_id (e.g., R1-TSEL-000 -> TSEL)
        $parts = explode('-', $packageId);
        if (count($parts) >= 2) {
            $providerCode = strtoupper($parts[1]);
            return $providerMap[$providerCode] ?? $providerCode;
        }

        return $packageId;
    }

    /**
     * Handle dashboard akses berdasarkan link_referral
     * Route: dash/{link_referral}
     */
    public function show($linkReferral)
    {
        // Get authenticated user from session
        $sessionUser = session('user');
        
        // Cek apakah link_referral adalah milik affiliate
        $affiliate = Affiliate::where('link_referral', $linkReferral)
            ->where('is_active', true)
            ->with('agents')
            ->first();

        if ($affiliate) {
            // Verify that authenticated user matches the dashboard owner
            if ($sessionUser && $sessionUser['email'] !== $affiliate->email) {
                return redirect()->route('login')->with('error', 'Anda tidak memiliki akses ke dashboard ini');
            }
            
            // Hitung statistik
            $totalAgents = $affiliate->agents->count();
            $activeAgents = $affiliate->agents->where('is_active', true)->count();
            $now = now();
            $activeAgentsThisMonth = $affiliate->agents->filter(function($agent) use ($now) {
                $createdAt = \Carbon\Carbon::parse($agent->created_at);
                return $agent->is_active && $createdAt->isSameMonth($now);
            })->count();
            $newAgentsThisMonth = $affiliate->agents->filter(function($agent) use ($now) {
                $createdAt = \Carbon\Carbon::parse($agent->created_at);
                return $createdAt->isSameMonth($now);
            })->count();

            return view('affiliate.dashboard', [
                'user' => $affiliate,
                'linkReferral' => $linkReferral,
                'portalType' => 'affiliate',
                'agents' => $affiliate->agents,
                'stats' => [
                    'totalAgents' => $totalAgents,
                    'activeAgents' => $activeAgents,
                    'activeAgentsThisMonth' => $activeAgentsThisMonth,
                    'newAgentsThisMonth' => $newAgentsThisMonth,
                    'saldoFee' => $affiliate->saldo_fee ?? 0,
                    'totalFee' => $affiliate->total_fee ?? 0,
                ]
            ]);
        }

        // Jika bukan affiliate, cek apakah milik freelance
        $freelance = Freelance::where('link_referral', $linkReferral)
            ->where('is_active', true)
            ->with('agents')
            ->first();

        if ($freelance) {
            // Verify that authenticated user matches the dashboard owner
            if ($sessionUser && $sessionUser['email'] !== $freelance->email) {
                return redirect()->route('login')->with('error', 'Anda tidak memiliki akses ke dashboard ini');
            }
            
            // Hitung statistik
            $totalAgents = $freelance->agents->count();
            $activeAgents = $freelance->agents->where('is_active', true)->count();
            $now = now();
            $activeAgentsThisMonth = $freelance->agents->filter(function($agent) use ($now) {
                $createdAt = \Carbon\Carbon::parse($agent->created_at);
                return $agent->is_active && $createdAt->isSameMonth($now);
            })->count();
            $newAgentsThisMonth = $freelance->agents->filter(function($agent) use ($now) {
                $createdAt = \Carbon\Carbon::parse($agent->created_at);
                return $createdAt->isSameMonth($now);
            })->count();

            return view('freelance.dashboard', [
                'user' => $freelance,
                'linkReferral' => $linkReferral,
                'portalType' => 'freelance',
                'agents' => $freelance->agents,
                'stats' => [
                    'totalAgents' => $totalAgents,
                    'activeAgents' => $activeAgents,
                    'activeAgentsThisMonth' => $activeAgentsThisMonth,
                    'newAgentsThisMonth' => $newAgentsThisMonth,
                    'saldoFee' => $freelance->saldo_fee ?? 0,
                    'totalFee' => $freelance->total_fee ?? 0,
                ]
            ]);
        }

        // Jika bukan freelance, cek apakah milik agent
        // Untuk agent pending, harus akses via /agent/pending?id=xxx
        // Untuk agent approved, bisa akses via /dash/{link_referal}
        $agent = Agent::where('link_referal', $linkReferral)
            ->whereIn('status', ['approved', 'approve'])
            ->first();

        if ($agent) {
            // Verify that authenticated user matches the dashboard owner
            if ($sessionUser && $sessionUser['email'] !== $agent->email) {
                return redirect()->route('login')->with('error', 'Anda tidak memiliki akses ke dashboard ini');
            }
            
            // Tampilkan dashboard normal untuk agent yang sudah approved
            return view('agent.dashboard', [
                'user' => $agent,
                'linkReferral' => $linkReferral,
                'portalType' => 'agent',
                'jenisTravelAgent' => $agent->jenis_travel ?? '',
                'linkReferalAgent' => $agent->link_referal ?? '',
                'isPending' => false,
                'stats' => [
                    'totalOrders' => 0, // TODO: implement orders count
                    'totalRevenue' => 0, // TODO: implement revenue
                    'activeBookings' => 0, // TODO: implement bookings count
                ]
            ]);
        }

        // Jika tidak ditemukan, redirect ke login
        return redirect()->route('login')->with('error', 'Login gagal. Akun Anda belum terdaftar. Silakan daftar terlebih dahulu atau hubungi tim support.');
    }

    /**
     * API endpoint untuk mendapatkan data affiliate berdasarkan link_referral
     */
    public function getAffiliateData($linkReferral)
    {
        $affiliate = Affiliate::where('link_referral', $linkReferral)
            ->where('is_active', true)
            ->with('agents')
            ->first();

        if (!$affiliate) {
            return response()->json([
                'success' => false,
                'message' => 'Affiliate tidak ditemukan atau tidak aktif'
            ], 404);
        }

        // Hitung statistik
        $totalAgents = $affiliate->agents()->count();
        $activeAgents = $affiliate->agents()->where('is_active', true)->count();
        $pendingAgents = $affiliate->agents()->where('is_active', false)->count();

        return response()->json([
            'success' => true,
            'data' => [
                'affiliate' => $affiliate,
                'stats' => [
                    'total_agents' => $totalAgents,
                    'active_agents' => $activeAgents,
                    'pending_agents' => $pendingAgents,
                ],
                'agents' => $affiliate->agents
            ]
        ]);
    }

    /**
     * API endpoint untuk mendapatkan data freelance berdasarkan link_referral
     */
    public function getFreelanceData($linkReferral)
    {
        $freelance = Freelance::where('link_referral', $linkReferral)
            ->where('is_active', true)
            ->with('agents')
            ->first();

        if (!$freelance) {
            return response()->json([
                'success' => false,
                'message' => 'Freelance tidak ditemukan atau tidak aktif'
            ], 404);
        }

        // Hitung statistik
        $totalAgents = $freelance->agents()->count();
        $activeAgents = $freelance->agents()->where('is_active', true)->count();
        $pendingAgents = $freelance->agents()->where('is_active', false)->count();

        return response()->json([
            'success' => true,
            'data' => [
                'freelance' => $freelance,
                'stats' => [
                    'total_agents' => $totalAgents,
                    'active_agents' => $activeAgents,
                    'pending_agents' => $pendingAgents,
                ],
                'agents' => $freelance->agents
            ]
        ]);
    }

    /**
     * Helper method untuk mendapatkan user berdasarkan link_referral
     */
    private function getUserByLinkReferral($linkReferral)
    {
        // Cek affiliate
        $affiliate = Affiliate::where('link_referral', $linkReferral)
            ->where('is_active', true)
            ->with('agents')
            ->first();

        if ($affiliate) {
            return [
                'user' => $affiliate,
                'portalType' => 'affiliate',
                'viewPath' => 'affiliate'
            ];
        }

        // Cek freelance
        $freelance = Freelance::where('link_referral', $linkReferral)
            ->where('is_active', true)
            ->with('agents')
            ->first();

        if ($freelance) {
            return [
                'user' => $freelance,
                'portalType' => 'freelance',
                'viewPath' => 'freelance'
            ];
        }

        // Cek agent (hanya yang sudah approved/approve, bukan pending)
        $agent = Agent::where('link_referal', $linkReferral)
            ->whereIn('status', ['approved', 'approve'])
            ->first();

        if ($agent) {
            return [
                'user' => $agent,
                'portalType' => 'agent',
                'viewPath' => 'agent',
                'jenisTravelAgent' => $agent->jenis_travel ?? '',
                'linkReferalAgent' => $agent->link_referal ?? '',
            ];
        }

        return null;
    }

    /**
     * Helper method untuk mendapatkan stats
     */
    private function getStats($user)
    {
        // Jika user adalah agent, return stats yang berbeda
        if ($user instanceof Agent) {
            $now = now();
            $startOfMonth = $now->copy()->startOfMonth();
            
            // Profit bulan ini diambil dari kolom saldo_bulan (reset setiap bulan)
            $monthlyProfit = $user->saldo_bulan ?? 0;
            
            // Total akumulasi tahun ini diambil dari kolom saldo_tahun (reset setiap tahun)
            $totalProfit = $user->saldo_tahun ?? 0;
            
            // Hitung total transaksi (jumlah pesanan) bulan ini
            $monthlyTransactions = \App\Models\Pesanan::where('kategori_channel', 'agent')
                ->where('channel_id', $user->id)
                ->whereHas('pembayaran', function($query) {
                    $query->where('status_pembayaran', 'selesai');
                })
                ->whereBetween('created_at', [$startOfMonth, $now])
                ->count();
            
            // Hitung total transaksi tahun ini
            $startOfYear = $now->copy()->startOfYear();
            $totalTransactions = \App\Models\Pesanan::where('kategori_channel', 'agent')
                ->where('channel_id', $user->id)
                ->whereHas('pembayaran', function($query) {
                    $query->where('status_pembayaran', 'selesai');
                })
                ->whereBetween('created_at', [$startOfYear, $now])
                ->count();
            
            return [
                'monthlyProfit' => $monthlyProfit,
                'totalProfit' => $totalProfit,
                'monthlyTransactions' => $monthlyTransactions,
                'totalTransactions' => $totalTransactions,
                'walletBalance' => 0, // TODO: implement dari table wallet/saldo
                'pendingWithdrawal' => 0, // TODO: implement dari table withdraw
            ];
        }

        // Untuk affiliate/freelance
        $totalAgents = $user->agents->count();
        $activeAgents = $user->agents->where('is_active', true)->count();
        $now = now();
        $activeAgentsThisMonth = $user->agents->filter(function($agent) use ($now) {
            $createdAt = \Carbon\Carbon::parse($agent->created_at);
            return $agent->is_active && $createdAt->isSameMonth($now);
        })->count();
        $newAgentsThisMonth = $user->agents->filter(function($agent) use ($now) {
            $createdAt = \Carbon\Carbon::parse($agent->created_at);
            return $createdAt->isSameMonth($now);
        })->count();

        return [
            'totalAgents' => $totalAgents,
            'activeAgents' => $activeAgents,
            'activeAgentsThisMonth' => $activeAgentsThisMonth,
            'newAgentsThisMonth' => $newAgentsThisMonth,
        ];
    }

    /**
     * Halaman Downlines/Agents
     */
    public function downlines($linkReferral)
    {
        $data = $this->getUserByLinkReferral($linkReferral);
        if (!$data) {
            return redirect()->route('login')->with('error', 'Login gagal. Akun Anda belum terdaftar. Silakan daftar terlebih dahulu atau hubungi tim support.');
        }

        // Debug: Log agents data
        \Log::info('Downlines Page - User Type: ' . $data['portalType']);
        \Log::info('Downlines Page - User ID: ' . $data['user']->id);
        \Log::info('Downlines Page - Agents Count: ' . $data['user']->agents->count());
        
        if ($data['user']->agents->count() > 0) {
            \Log::info('Downlines Page - First Agent:', $data['user']->agents->first()->toArray());
        }

        return view($data['viewPath'] . '.downlines', [
            'user' => $data['user'],
            'linkReferral' => $linkReferral,
            'portalType' => $data['portalType'],
            'agents' => $data['user']->agents,
            'stats' => $this->getStats($data['user'])
        ]);
    }

    /**
     * Halaman Rewards
     */
    public function rewards($linkReferral)
    {
        $data = $this->getUserByLinkReferral($linkReferral);
        if (!$data) {
            return redirect()->route('login')->with('error', 'Login gagal. Akun Anda belum terdaftar. Silakan daftar terlebih dahulu atau hubungi tim support.');
        }

        return view($data['viewPath'] . '.rewards', [
            'user' => $data['user'],
            'linkReferral' => $linkReferral,
            'portalType' => $data['portalType'],
            'stats' => $this->getStats($data['user'])
        ]);
    }

    /**
     * Halaman Points History
     */
    public function pointsHistory($linkReferral)
    {
        $data = $this->getUserByLinkReferral($linkReferral);
        if (!$data) {
            return redirect()->route('login')->with('error', 'Login gagal. Akun Anda belum terdaftar. Silakan daftar terlebih dahulu atau hubungi tim support.');
        }

        return view($data['viewPath'] . '.points-history', [
            'user' => $data['user'],
            'linkReferral' => $linkReferral,
            'portalType' => $data['portalType'],
            'stats' => $this->getStats($data['user'])
        ]);
    }

    /**
     * Halaman Profile
     */
    public function profile($linkReferral)
    {
        $data = $this->getUserByLinkReferral($linkReferral);
        if (!$data) {
            return redirect()->route('login')->with('error', 'Login gagal. Akun Anda belum terdaftar. Silakan daftar terlebih dahulu atau hubungi tim support.');
        }

        return view($data['viewPath'] . '.profile', [
            'user' => $data['user'],
            'linkReferral' => $linkReferral,
            'portalType' => $data['portalType'],
            'stats' => $this->getStats($data['user'])
        ]);
    }

    /**
     * Halaman Invite
     */
    public function invite($linkReferral)
    {
        $data = $this->getUserByLinkReferral($linkReferral);
        if (!$data) {
            return redirect()->route('login')->with('error', 'Login gagal. Akun Anda belum terdaftar. Silakan daftar terlebih dahulu atau hubungi tim support.');
        }

        return view($data['viewPath'] . '.invite', [
            'user' => $data['user'],
            'linkReferral' => $linkReferral,
            'portalType' => $data['portalType'],
            'stats' => $this->getStats($data['user'])
        ]);
    }
    
    /**
     * Halaman Order (Agent)
     */
    public function order($linkReferral)
    {
        $data = $this->getUserByLinkReferral($linkReferral);
        if (!$data) {
            return redirect()->route('login')->with('error', 'Login gagal. Akun Anda belum terdaftar. Silakan daftar terlebih dahulu atau hubungi tim support.');
        }

        // Get packages from database
        $packages = \App\Models\Produk::orderBy('created_at', 'desc')->get();
        
        // Get agents based on portal type
        $agents = [];
        if ($data['portalType'] === 'affiliate' || $data['portalType'] === 'freelance') {
            $agents = $data['user']->agents;
        }
        
        // Debug
        \Log::info('Dashboard Order - Total packages: ' . $packages->count());
        \Log::info('Dashboard Order - Total agents: ' . count($agents));

        return view($data['viewPath'] . '.order', [
            'user' => $data['user'],
            'linkReferral' => $linkReferral,
            'portalType' => $data['portalType'],
            'stats' => $this->getStats($data['user']),
            'packages' => $packages,
            'agents' => $agents
        ]);
    }

    /**
     * Halaman Checkout
     */
    public function checkout($linkReferral)
    {
        $data = $this->getUserByLinkReferral($linkReferral);
        if (!$data) {
            abort(404, 'Dashboard tidak ditemukan atau tidak aktif');
        }

        return view($data['viewPath'] . '.checkout', [
            'user' => $data['user'],
            'linkReferral' => $linkReferral,
            'portalType' => $data['portalType'],
            'stats' => $this->getStats($data['user'])
        ]);
    }

    /**
     * Halaman History (Agent)
     */
    public function history($linkReferral)
    {
        $data = $this->getUserByLinkReferral($linkReferral);
        if (!$data) {
            return redirect()->route('login')->with('error', 'Login gagal. Akun Anda belum terdaftar. Silakan daftar terlebih dahulu atau hubungi tim support.');
        }

        $user = $data['user'];
        $transactions = [];

        // Jika user adalah agent, ambil data transaksi dari database
        if ($user instanceof \App\Models\Agent) {
            // Ambil data pembayaran (batch) beserta pesanan
            $transactions = \App\Models\Pembayaran::where('agent_id', $user->id)
                ->with(['pesanan.produk'])
                ->orderBy('created_at', 'desc')
                ->get()
                ->map(function($pembayaran) {
                    // Map items pesanan terlebih dahulu
                    $items = $pembayaran->pesanan->map(function($pesanan) {
                        // Map status aktivasi pesanan
                        $itemStatus = match($pesanan->status_aktivasi) {
                            'berhasil' => 'completed',
                            'proses' => 'processing',
                            'gagal' => 'failed',
                            default => 'pending'
                        };

                        return [
                            'msisdn' => $pesanan->msisdn,
                            'provider' => $pesanan->produk->provider ?? 'N/A',
                            'packageName' => $pesanan->nama_paket ?? ($pesanan->produk->nama_paket ?? 'N/A'),
                            'price' => $pesanan->harga_jual,
                            'status' => $itemStatus
                        ];
                    })->toArray();

                    // Tentukan status batch berdasarkan status pesanan di dalamnya
                    $statusCounts = collect($items)->countBy('status');
                    $totalItems = count($items);
                    
                    // Logic status batch:
                    // - completed: jika semua item completed
                    // - processing: jika ada item yang processing atau campuran
                    // - pending: jika semua item pending
                    $batchStatus = 'pending';
                    if ($statusCounts->get('completed', 0) === $totalItems) {
                        $batchStatus = 'completed';
                    } elseif ($statusCounts->get('processing', 0) > 0 || $statusCounts->get('completed', 0) > 0) {
                        $batchStatus = 'processing';
                    }

                    return [
                        'id' => (string)$pembayaran->id,
                        'batchId' => $pembayaran->batch_id,
                        'batchName' => $pembayaran->nama_batch ?? 'Batch ' . $pembayaran->batch_id,
                        'createdAt' => $pembayaran->created_at->toISOString(),
                        'totalAmount' => $pembayaran->total_pembayaran,
                        'status' => $batchStatus,
                        'items' => $items
                    ];
                })->toArray();
        }

        return view($data['viewPath'] . '.history', [
            'user' => $data['user'],
            'linkReferral' => $linkReferral,
            'portalType' => $data['portalType'],
            'stats' => $this->getStats($data['user']),
            'transactions' => $transactions
        ]);
    }

    /**
     * Halaman Wallet (Agent)
     */
    public function wallet($linkReferral)
    {
        $data = $this->getUserByLinkReferral($linkReferral);
        if (!$data) {
            return redirect()->route('login')->with('error', 'Login gagal. Akun Anda belum terdaftar. Silakan daftar terlebih dahulu atau hubungi tim support.');
        }

        $user = $data['user'];
        $walletBalance = [
            'balance' => 0,
            'pendingWithdrawal' => 0
        ];
        
        $withdrawalHistory = [];
        
        // Jika user adalah agent, ambil saldo dari database
        if ($user instanceof \App\Models\Agent) {
            $walletBalance['balance'] = $user->saldo ?? 0;
            
            // Hitung pending withdrawal dari table withdraw
            $pendingWithdrawals = \App\Models\Withdraw::where('agent_id', $user->id)
                ->where('status', 'pending')
                ->sum('jumlah');
            $walletBalance['pendingWithdrawal'] = $pendingWithdrawals;
            
            // Ambil withdrawal history dari database
            $withdrawalHistory = \App\Models\Withdraw::where('agent_id', $user->id)
                ->with('rekening')
                ->orderBy('created_at', 'desc')
                ->get()
                ->map(function($withdraw) {
                    return [
                        'id' => $withdraw->id,
                        'date' => $withdraw->created_at,
                        'amount' => $withdraw->jumlah,
                        'bankName' => $withdraw->rekening->bank ?? 'N/A',
                        'accountNumber' => '****' . substr($withdraw->rekening->nomor_rekening ?? '', -4),
                        'status' => $withdraw->status,
                        'keterangan' => $withdraw->keterangan,
                        'alasan_reject' => $withdraw->alasan_reject
                    ];
                })->toArray();
        }

        return view($data['viewPath'] . '.wallet', [
            'user' => $data['user'],
            'linkReferral' => $linkReferral,
            'portalType' => $data['portalType'],
            'stats' => $this->getStats($data['user']),
            'walletBalance' => $walletBalance,
            'withdrawalHistory' => $withdrawalHistory
        ]);
    }

    /**
     * Halaman History Profit (Agent)
     */
    public function historyProfit($linkReferral)
    {
        $data = $this->getUserByLinkReferral($linkReferral);
        if (!$data) {
            return redirect()->route('login')->with('error', 'Login gagal. Akun Anda belum terdaftar. Silakan daftar terlebih dahulu atau hubungi tim support.');
        }

        $user = $data['user'];
        $profitData = [
            'current_balance' => 0,
            'monthly_profit' => 0,
            'yearly_profit' => 0,
            'monthly_history' => [],
            'yearly_history' => []
        ];
        
        // Jika user adalah agent, ambil data profit
        if ($user instanceof \App\Models\Agent) {
            $profitData['current_balance'] = $user->saldo ?? 0;
            $profitData['monthly_profit'] = $user->saldo_bulan ?? 0;
            $profitData['yearly_profit'] = $user->saldo_tahun ?? 0;
            
            // Ambil history profit per bulan dari pesanan
            $monthlyHistory = \App\Models\Pesanan::where('kategori_channel', 'agent')
                ->where('channel_id', $user->id)
                ->whereHas('pembayaran', function($query) {
                    $query->whereIn('status_pembayaran', ['selesai', 'berhasil', 'SUCCESS']);
                })
                ->selectRaw('DATE_FORMAT(created_at, "%Y-%m") as month, SUM(profit) as total_profit, COUNT(*) as total_transactions')
                ->groupBy('month')
                ->orderBy('month', 'DESC')
                ->limit(12)
                ->get();
            
            // Untuk setiap bulan, ambil detail transaksinya dan restructure ke array
            $monthlyHistoryArray = [];
            foreach ($monthlyHistory as $monthData) {
                $details = \App\Models\Pesanan::where('kategori_channel', 'agent')
                    ->where('channel_id', $user->id)
                    ->whereHas('pembayaran', function($query) {
                        $query->whereIn('status_pembayaran', ['selesai', 'berhasil', 'SUCCESS']);
                    })
                    ->with('produk:id,nama_paket')
                    ->whereRaw('DATE_FORMAT(created_at, "%Y-%m") = ?', [$monthData->month])
                    ->select('id', 'produk_id', 'profit', 'created_at')
                    ->orderBy('created_at', 'DESC')
                    ->get()
                    ->map(function($pesanan) {
                        return [
                            'date' => $pesanan->created_at->format('d-m-Y'),
                            'product_name' => $pesanan->produk->nama_paket ?? 'N/A',
                            'profit' => $pesanan->profit
                        ];
                    })->toArray();
                
                $monthlyHistoryArray[] = [
                    'month' => $monthData->month,
                    'total_profit' => $monthData->total_profit,
                    'total_transactions' => $monthData->total_transactions,
                    'details' => $details
                ];
            }
            
            $profitData['monthly_history'] = $monthlyHistoryArray;
            
            // Ambil history profit per tahun dari pesanan
            $profitData['yearly_history'] = \App\Models\Pesanan::where('kategori_channel', 'agent')
                ->where('channel_id', $user->id)
                ->whereHas('pembayaran', function($query) {
                    $query->whereIn('status_pembayaran', ['selesai', 'berhasil', 'SUCCESS']);
                })
                ->selectRaw('YEAR(created_at) as year, SUM(profit) as total_profit, COUNT(*) as total_transactions')
                ->groupBy('year')
                ->orderBy('year', 'DESC')
                ->get();
        }

        return view($data['viewPath'] . '.history-profit', [
            'user' => $data['user'],
            'linkReferral' => $linkReferral,
            'portalType' => $data['portalType'],
            'profitData' => $profitData
        ]);
    }

    /**
     * Detail History Profit per Bulan
     */
    public function historyProfitDetail($linkReferral, $month)
    {
        $data = $this->getUserByLinkReferral($linkReferral);
        if (!$data) {
            return redirect()->route('login')->with('error', 'Login gagal. Akun Anda belum terdaftar. Silakan daftar terlebih dahulu atau hubungi tim support.');
        }

        $user = $data['user'];
        $details = [];
        
        // Jika user adalah agent, ambil detail transaksi bulan ini
        if ($user instanceof \App\Models\Agent) {
            $details = \App\Models\Pesanan::where('kategori_channel', 'agent')
                ->where('channel_id', $user->id)
                ->whereHas('pembayaran', function($query) {
                    $query->whereIn('status_pembayaran', ['selesai', 'berhasil', 'SUCCESS']);
                })
                ->with('produk:id,nama_paket')
                ->whereRaw('DATE_FORMAT(created_at, "%Y-%m") = ?', [$month])
                ->select('id', 'produk_id', 'profit', 'created_at')
                ->orderBy('created_at', 'DESC')
                ->get()
                ->map(function($pesanan) {
                    return [
                        'date' => $pesanan->created_at->format('d-m-Y H:i'),
                        'product_name' => $pesanan->produk->nama_paket ?? 'N/A',
                        'profit' => $pesanan->profit
                    ];
                });
        }

        $totalProfit = $details->sum('profit');
        $totalTransactions = $details->count();

        return view($data['viewPath'] . '.history-profit-detail', [
            'user' => $data['user'],
            'linkReferral' => $linkReferral,
            'portalType' => $data['portalType'],
            'month' => $month,
            'details' => $details,
            'totalProfit' => $totalProfit,
            'totalTransactions' => $totalTransactions
        ]);
    }


    /**
     * Halaman Withdraw (Agent)
     */
    public function withdraw($linkReferral)
    {
        $data = $this->getUserByLinkReferral($linkReferral);
        if (!$data) {
            return redirect()->route('login')->with('error', 'Login gagal. Akun Anda belum terdaftar. Silakan daftar terlebih dahulu atau hubungi tim support.');
        }

        $user = $data['user'];
        $walletBalance = [
            'balance' => 0,
            'pendingWithdrawal' => 0
        ];
        
        // Load rekening untuk user (agent)
        $rekenings = [];
        if ($data['portalType'] === 'agent') {
            // Ambil saldo dari database
            $walletBalance['balance'] = $user->saldo ?? 0;
            
            // Hitung pending withdrawal dari table withdraw
            $pendingWithdrawals = \App\Models\Withdraw::where('agent_id', $user->id)
                ->where('status', 'pending')
                ->sum('jumlah');
            $walletBalance['pendingWithdrawal'] = $pendingWithdrawals;
            
            $rekenings = \App\Models\Rekening::where('agent_id', $data['user']->id)
                ->orderBy('created_at', 'desc')
                ->get()
                ->map(function($r) {
                    return [
                        'id' => $r->id,
                        'bankName' => $r->bank,
                        'accountNumber' => $r->nomor_rekening,
                        'accountName' => $r->nama_rekening,
                        'isDefault' => false
                    ];
                });
        }

        return view($data['viewPath'] . '.withdraw', [
            'user' => $data['user'],
            'linkReferral' => $linkReferral,
            'portalType' => $data['portalType'],
            'stats' => $this->getStats($data['user']),
            'rekenings' => $rekenings,
            'walletBalance' => $walletBalance
        ]);
    }

    /**
     * Halaman Referrals (Agent)
     */
    public function referrals($linkReferral)
    {
        $data = $this->getUserByLinkReferral($linkReferral);
        if (!$data) {
            return redirect()->route('login')->with('error', 'Login gagal. Akun Anda belum terdaftar. Silakan daftar terlebih dahulu atau hubungi tim support.');
        }

        // Initialize default profitData
        $profitData = [
            'current_balance' => 0,
            'monthly_profit' => 0,
            'yearly_profit' => 0,
            'monthly_history' => [],
            'yearly_history' => []
        ];

        $viewData = [
            'user' => $data['user'],
            'linkReferral' => $linkReferral,
            'portalType' => $data['portalType'],
            'stats' => $this->getStats($data['user'])
        ];

        // Add linkReferalAgent for agents
        if ($data['portalType'] === 'agent' && isset($data['user']->link_referal)) {
            $viewData['linkReferalAgent'] = $data['user']->link_referal;
        }

        // Jika user adalah agent, ambil data pesanan agent sendiri untuk ditampilkan di halaman referrals
        if ($data['user'] instanceof \App\Models\Agent) {
            $user = $data['user'];
            
            // --- Logic for Profit Data (Graph) ---
            $profitData['current_balance'] = $user->saldo ?? 0;
            $profitData['monthly_profit'] = $user->saldo_bulan ?? 0;
            $profitData['yearly_profit'] = $user->saldo_tahun ?? 0;
            
            // Ambil history profit per bulan dari pesanan
            $monthlyHistory = \App\Models\Pesanan::where('kategori_channel', 'agent')
                ->where('channel_id', $user->id)
                ->whereHas('pembayaran', function($query) {
                    $query->whereIn('status_pembayaran', ['selesai', 'berhasil', 'SUCCESS']);
                })
                ->selectRaw('DATE_FORMAT(created_at, "%Y-%m") as month, SUM(profit) as total_profit, COUNT(*) as total_transactions')
                ->groupBy('month')
                ->orderBy('month', 'DESC')
                ->limit(12)
                ->get();
            
            // Untuk setiap bulan, ambil detail transaksinya dan restructure ke array
            $monthlyHistoryArray = [];
            foreach ($monthlyHistory as $monthData) {
                // Simplified details fetch for graph (optional, mainly used in history-profit page, but let's keep it consistent)
                $details = \App\Models\Pesanan::where('kategori_channel', 'agent')
                    ->where('channel_id', $user->id)
                    ->whereHas('pembayaran', function($query) {
                        $query->whereIn('status_pembayaran', ['selesai', 'berhasil', 'SUCCESS']);
                    })
                    ->with('produk:id,nama_paket')
                    ->whereRaw('DATE_FORMAT(created_at, "%Y-%m") = ?', [$monthData->month])
                    ->select('id', 'produk_id', 'profit', 'created_at')
                    ->orderBy('created_at', 'DESC')
                    ->get()
                    ->map(function($pesanan) {
                        return [
                            'id' => $pesanan->id,
                            'product' => $pesanan->produk->nama_paket ?? '-',
                            'profit' => $pesanan->profit,
                            'date' => $pesanan->created_at->format('d M Y')
                        ];
                    });
                
                $monthlyHistoryArray[] = [
                    'month' => $monthData->month,
                    'total_profit' => $monthData->total_profit,
                    'total_transactions' => $monthData->total_transactions,
                    'details' => $details
                ];
            }
            $profitData['monthly_history'] = $monthlyHistoryArray;
            
            // Ambil history profit per tahun dari pesanan
            $profitData['yearly_history'] = \App\Models\Pesanan::where('kategori_channel', 'agent')
                ->where('channel_id', $user->id)
                ->whereHas('pembayaran', function($query) {
                    $query->whereIn('status_pembayaran', ['selesai', 'berhasil', 'SUCCESS']);
                })
                ->selectRaw('YEAR(created_at) as year, SUM(profit) as total_profit, COUNT(*) as total_transactions')
                ->groupBy('year')
                ->orderBy('year', 'DESC')
                ->get();

            // --- Logic for Referral Orders Table ---
            // Ambil pesanan agent ini
            $referralOrders = \App\Models\Pesanan::where('kategori_channel', 'agent')
                ->where('channel_id', $user->id)
                ->with(['produk', 'pembayaran'])
                ->orderBy('created_at', 'desc')
                ->get();

            // Hitung komisi/profit dari pesanan yang berhasil (status_aktivasi = 'berhasil')
            $totalCommission = $referralOrders->filter(function($order) {
                return $order->status_aktivasi === 'berhasil';
            })->sum('profit');

            // Hitung komisi pending dari pesanan yang masih proses (status_aktivasi = 'proses')
            $pendingCommission = $referralOrders->filter(function($order) {
                return $order->status_aktivasi === 'proses';
            })->sum('profit');

            // Map data untuk view
            $referralData = $referralOrders->map(function($order) {
                // Map status aktivasi pesanan
                $status = match($order->status_aktivasi) {
                    'berhasil' => 'sukses',
                    'proses' => 'proses',
                    'gagal' => 'batal',
                    default => 'proses'
                };

                return [
                    'id' => (string)$order->id,
                    'msisdn' => $order->msisdn,
                    'provider' => $order->produk->provider ?? 'N/A',
                    'packageName' => $order->nama_paket ?? ($order->produk->nama_paket ?? 'N/A'),
                    'orderDate' => $order->created_at->toISOString(),
                    'sellPrice' => $order->harga_jual,
                    'commission' => $order->profit,
                    'status' => $status
                ];
            })->toArray();

            $viewData['totalCommission'] = $totalCommission;
            $viewData['pendingCommission'] = $pendingCommission;
            $viewData['referralOrders'] = $referralData;
        }

        // Add profitData to viewData
        $viewData['profitData'] = $profitData;

        return view($data['viewPath'] . '.referrals', $viewData);
    }

    /**
     * Halaman Catalog (Agent)
     */
    public function catalog($linkReferral)
    {
        $data = $this->getUserByLinkReferral($linkReferral);
        if (!$data) {
            return redirect()->route('login')->with('error', 'Login gagal. Akun Anda belum terdaftar. Silakan daftar terlebih dahulu atau hubungi tim support.');
        }

        // Get packages from database
        $packages = \App\Models\Produk::orderBy('created_at', 'desc')->get();
        
        // Debug
        \Log::info('Dashboard Catalog - Total packages: ' . $packages->count());

        return view($data['viewPath'] . '.catalog', [
            'user' => $data['user'],
            'linkReferral' => $linkReferral,
            'portalType' => $data['portalType'],
            'stats' => $this->getStats($data['user']),
            'packages' => $packages
        ]);
    }

    /**
     * Direct Freelance Dashboard (without link_referral in URL)
     * Used by route /freelance/dashboard
     */
    public function freelanceDashboard(Request $request)
    {
        // Get freelance ID from session or request
        $freelanceId = $request->query('id') ?? $request->session()->get('freelance_id');
        
        if (!$freelanceId) {
            return redirect('/freelance/login');
        }

        $freelance = Freelance::find($freelanceId);
        
        if (!$freelance || !$freelance->is_active) {
            return redirect('/freelance/login');
        }

        // Load agents relationship
        $freelance->load('agents');

        // Hitung statistik
        $totalAgents = $freelance->agents->count();
        $activeAgents = $freelance->agents->where('is_active', true)->count();
        $now = now();
        $activeAgentsThisMonth = $freelance->agents->filter(function($agent) use ($now) {
            $createdAt = \Carbon\Carbon::parse($agent->created_at);
            return $agent->is_active && $createdAt->isSameMonth($now);
        })->count();
        $newAgentsThisMonth = $freelance->agents->filter(function($agent) use ($now) {
            $createdAt = \Carbon\Carbon::parse($agent->created_at);
            return $createdAt->isSameMonth($now);
        })->count();

        return view('freelance.dashboard', [
            'user' => $freelance,
            'linkReferral' => $freelance->link_referral ?? '',
            'portalType' => 'freelance',
            'agents' => $freelance->agents,
            'stats' => [
                'totalAgents' => $totalAgents,
                'activeAgents' => $activeAgents,
                'activeAgentsThisMonth' => $activeAgentsThisMonth,
                'newAgentsThisMonth' => $newAgentsThisMonth,
                'saldoFee' => $freelance->saldo_fee ?? 0,
                'totalFee' => $freelance->total_fee ?? 0,
            ]
        ]);
    }

    /**
     * Direct Affiliate Dashboard (without link_referral in URL)
     * Used by route /affiliate/dashboard
     */
    public function affiliateDashboard(Request $request)
    {
        // Get affiliate ID from session or request
        $affiliateId = $request->query('id') ?? $request->session()->get('affiliate_id');
        
        if (!$affiliateId) {
            return redirect('/affiliate/login');
        }

        $affiliate = Affiliate::find($affiliateId);
        
        if (!$affiliate || !$affiliate->is_active) {
            return redirect('/affiliate/login');
        }

        // Load agents relationship
        $affiliate->load('agents');

        // Hitung statistik
        $totalAgents = $affiliate->agents->count();
        $activeAgents = $affiliate->agents->where('is_active', true)->count();
        $now = now();
        $activeAgentsThisMonth = $affiliate->agents->filter(function($agent) use ($now) {
            $createdAt = \Carbon\Carbon::parse($agent->created_at);
            return $agent->is_active && $createdAt->isSameMonth($now);
        })->count();
        $newAgentsThisMonth = $affiliate->agents->filter(function($agent) use ($now) {
            $createdAt = \Carbon\Carbon::parse($agent->created_at);
            return $createdAt->isSameMonth($now);
        })->count();

        return view('affiliate.dashboard', [
            'user' => $affiliate,
            'linkReferral' => $affiliate->link_referral ?? '',
            'portalType' => 'affiliate',
            'agents' => $affiliate->agents,
            'stats' => [
                'totalAgents' => $totalAgents,
                'activeAgents' => $activeAgents,
                'activeAgentsThisMonth' => $activeAgentsThisMonth,
                'newAgentsThisMonth' => $newAgentsThisMonth,
                'saldoFee' => $affiliate->saldo_fee ?? 0,
                'totalFee' => $affiliate->total_fee ?? 0,
            ]
        ]);
    }

    /**
     * Store new agent from downlines page
     * Route: POST /dash/{link_referral}/downlines/agent
     */
    public function storeDownlineAgent(Request $request, $linkReferral)
    {
        // Validate request
        $validated = $request->validate([
            'email' => 'required|email|unique:agent,email',
            'nama_pic' => 'required|string|max:255',
            'no_hp' => 'required|string|max:20',
            'provinsi' => 'required|string|max:255',
            'kabupaten_kota' => 'required|string|max:255',
            'alamat_lengkap' => 'required|string',
            'nama_travel' => 'nullable|string|max:255',
            'jenis_travel' => 'nullable|string|max:255',
            'total_traveller' => 'nullable|integer',
            'lat' => 'nullable|numeric',
            'long' => 'nullable|numeric',
            'link_gmaps' => 'nullable|string',
            'logo' => 'nullable|image|mimes:png,jpg,jpeg,gif|max:2048',
        ]);

        // Cari affiliate atau freelance berdasarkan link_referral
        $affiliate = Affiliate::where('link_referral', $linkReferral)
            ->where('is_active', true)
            ->first();

        $freelance = null;
        if (!$affiliate) {
            $freelance = Freelance::where('link_referral', $linkReferral)
                ->where('is_active', true)
                ->first();
        }

        // Jika tidak ditemukan affiliate maupun freelance
        if (!$affiliate && !$freelance) {
            return response()->json([
                'message' => 'Affiliate atau Freelance tidak ditemukan'
            ], 404);
        }

        // Siapkan data agent
        $agentData = [
            'email' => $validated['email'],
            'nama_pic' => $validated['nama_pic'],
            'no_hp' => $validated['no_hp'],
            'provinsi' => $validated['provinsi'],
            'kabupaten_kota' => $validated['kabupaten_kota'],
            'alamat_lengkap' => $validated['alamat_lengkap'],
            'kategori_agent' => 'Host', // Otomatis set ke Host
            'status' => 'pending', // Status pending, perlu approval
        ];

        // Set affiliate_id atau freelance_id
        if ($affiliate) {
            $agentData['affiliate_id'] = $affiliate->id;
        } else {
            $agentData['freelance_id'] = $freelance->id;
        }

        // Optional fields
        if (!empty($validated['nama_travel'])) {
            $agentData['nama_travel'] = $validated['nama_travel'];
        }
        if (!empty($validated['jenis_travel'])) {
            $agentData['jenis_travel'] = $validated['jenis_travel'];
        }
        if (isset($validated['total_traveller'])) {
            $agentData['total_traveller'] = $validated['total_traveller'];
        }
        if (!empty($validated['lat'])) {
            $agentData['lat'] = $validated['lat'];
        }
        if (!empty($validated['long'])) {
            $agentData['long'] = $validated['long'];
        }
        if (!empty($validated['link_gmaps'])) {
            $agentData['link_gmaps'] = $validated['link_gmaps'];
        }

        // Handle logo upload
        if ($request->hasFile('logo')) {
            $logoPath = $request->file('logo')->store('agents/logos', 'public');
            $agentData['logo'] = $logoPath;
        }

        // Create agent
        $agent = Agent::create($agentData);

        return response()->json([
            'message' => 'Agent berhasil ditambahkan',
            'agent' => $agent
        ], 201);
    }

    /**
     * Halaman Transactions
     */
    public function transactions($linkReferral)
    {
        $data = $this->getUserByLinkReferral($linkReferral);
        if (!$data) {
            return redirect()->route('login')->with('error', 'Login gagal. Akun Anda belum terdaftar. Silakan daftar terlebih dahulu atau hubungi tim support.');
        }

        // Get transactions from pembayaran table based on affiliate_id
        $transactions = [];
        
        if ($data['portalType'] === 'affiliate') {
            // Get pembayaran data where agent belongs to this affiliate
            $pembayaranData = \App\Models\Pembayaran::with([
                'agent.affiliate',
                'produk',
                'pesanan' => function($query) {
                    $query->orderBy('created_at', 'asc');
                }
            ])
            ->whereHas('agent', function($query) use ($data) {
                $query->where('affiliate_id', $data['user']->id);
            })
            ->orderBy('created_at', 'desc')
            ->get();

            // Transform data for frontend
            foreach ($pembayaranData as $pembayaran) {
                $agent = $pembayaran->agent;
                
                // Parse detail_pesanan to get detailed items
                $detailPesanan = null;
                $parsedItems = [];
                
                if ($pembayaran->detail_pesanan) {
                    $detailPesanan = json_decode($pembayaran->detail_pesanan, true);
                    
                    // Extract items from detail_pesanan
                    if ($detailPesanan && isset($detailPesanan['items']) && is_array($detailPesanan['items'])) {
                        foreach ($detailPesanan['items'] as $item) {
                            $packageId = $item['package_id'] ?? '';
                            $provider = $this->extractProviderFromPackageId($packageId);
                            
                            // Get pricing details for this package
                            $price = 0;
                            if (isset($detailPesanan['pricing_details']) && is_array($detailPesanan['pricing_details'])) {
                                foreach ($detailPesanan['pricing_details'] as $pricing) {
                                    if ($pricing['package_id'] === $packageId) {
                                        $price = $pricing['toko_harga_jual'] ?? 0;
                                        break;
                                    }
                                }
                            }
                            
                            // Determine item status based on pembayaran status
                            $itemStatus = 'pending';
                            if ($pembayaran->status_pembayaran === \App\Models\Pembayaran::STATUS_SUCCESS) {
                                $itemStatus = 'completed';
                            } elseif ($pembayaran->status_pembayaran === \App\Models\Pembayaran::STATUS_VERIFY) {
                                $itemStatus = 'processing';
                            } elseif ($pembayaran->status_pembayaran === \App\Models\Pembayaran::STATUS_FAILED) {
                                $itemStatus = 'failed';
                            }
                            // WAITING remains as 'pending'
                            
                            $parsedItems[] = [
                                'msisdn' => $item['msisdn'] ?? '-',
                                'provider' => $provider,
                                'packageName' => $item['package_name'] ?? $packageId,
                                'price' => (int) $price,
                                'status' => $itemStatus,
                            ];
                        }
                    }
                }
                
                // Map pesanan items (fallback if detail_pesanan is empty)
                $items = [];
                if (!empty($parsedItems)) {
                    $items = collect($parsedItems);
                } else {
                    $items = $pembayaran->pesanan->map(function($pesanan) {
                        $status = 'pending';
                        $statusAktivasi = strtolower($pesanan->status_aktivasi ?? '');
                        
                        if ($statusAktivasi === 'berhasil' || $statusAktivasi === 'success') {
                            $status = 'completed';
                        } elseif ($statusAktivasi === 'proses' || $statusAktivasi === 'process' || $statusAktivasi === 'processing') {
                            $status = 'processing';
                        } elseif ($statusAktivasi === 'gagal' || $statusAktivasi === 'failed') {
                            $status = 'failed';
                        }

                        return [
                            'id' => $pesanan->id,
                            'msisdn' => $pesanan->msisdn,
                            'provider' => $pesanan->provider ?? '-',
                            'packageName' => $pesanan->nama_paket ?? '-',
                            'price' => (int) ($pesanan->harga_jual ?? 0),
                            'status' => $status,
                            'createdAt' => $pesanan->created_at->toIso8601String(),
                        ];
                    });
                }

                // Determine batch status based on pembayaran status
                $batchStatus = 'pending';
                
                if ($pembayaran->status_pembayaran === \App\Models\Pembayaran::STATUS_SUCCESS) {
                    $batchStatus = 'completed';
                } elseif ($pembayaran->status_pembayaran === \App\Models\Pembayaran::STATUS_FAILED) {
                    $batchStatus = 'failed';
                } elseif ($pembayaran->status_pembayaran === \App\Models\Pembayaran::STATUS_VERIFY || $pembayaran->status_pembayaran === \App\Models\Pembayaran::STATUS_WAITING) {
                    $batchStatus = 'pending'; // Menunggu Pembayaran
                }

                $transactions[] = [
                    'id' => $pembayaran->id,
                    'batchId' => $pembayaran->batch_id,
                    'batchName' => $pembayaran->batch_name ?? 'Batch ' . $pembayaran->batch_id,
                    'agentName' => $agent->nama_pic ?? '-',
                    'agentPhone' => $agent->no_hp ?? '-',
                    'travelName' => $agent->nama_travel ?? '-',
                    'territory' => $agent->kabupaten_kota ?? '-',
                    'totalAmount' => (int) $pembayaran->total_pembayaran,
                    'margin' => (int) $pembayaran->fee_affiliate, // Use fee_affiliate as margin
                    'status' => $batchStatus,
                    'createdAt' => $pembayaran->created_at->toIso8601String(),
                    'items' => is_array($items) ? $items : $items->toArray(), // Items to display in detail
                    'msisdn' => $pembayaran->msisdn ?? '-', // Main msisdn from pembayaran
                ];
            }
        }

        return view($data['viewPath'] . '.transactions', [
            'user' => $data['user'],
            'linkReferral' => $linkReferral,
            'portalType' => $data['portalType'],
            'stats' => $this->getStats($data['user']),
            'transactions' => $transactions
        ]);
    }
}
