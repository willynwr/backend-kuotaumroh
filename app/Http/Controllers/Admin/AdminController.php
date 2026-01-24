<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Agent;
use App\Models\Affiliate;
use App\Models\Freelance;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class AdminController extends Controller
{
    private function normalizeIndonesianMsisdn(string $input): string
    {
        $digits = preg_replace('/\D+/', '', $input);
        if ($digits === null) return '';
        if (str_starts_with($digits, '62')) return $digits;
        if (str_starts_with($digits, '0')) return '62' . substr($digits, 1);
        return '62' . $digits;
    }

    private function redirectBackTo(string $fallbackRouteName, Request $request)
    {
        $redirectTo = $request->input('redirect_to');
        if (is_string($redirectTo) && str_starts_with($redirectTo, '/admin/users')) {
            return redirect($redirectTo);
        }
        return redirect()->route($fallbackRouteName);
    }
    public function dashboard()
    {
        // Calculate MTD and YTD revenue from QRIS payments with status 'berhasil'
        $currentMonth = now()->month;
        $currentYear = now()->year;
        
        // MTD (Month-To-Date) Revenue
        $revenueMTD = \App\Models\Pembayaran::where('metode_pembayaran', 'qris')
            ->where('status_pembayaran', 'berhasil')
            ->whereMonth('created_at', $currentMonth)
            ->whereYear('created_at', $currentYear)
            ->sum('total_pembayaran');

        // YTD (Year-To-Date) Revenue
        $revenueYTD = \App\Models\Pembayaran::where('metode_pembayaran', 'qris')
            ->where('status_pembayaran', 'berhasil')
            ->whereYear('created_at', $currentYear)
            ->sum('total_pembayaran');

        // Count active agents (status = 'approve')
        $activeAgents = Agent::where('status', 'approve')->count();
        
        // Count active affiliates (is_active = 1)
        $activeAffiliates = Affiliate::where('is_active', 1)->count();
        
        // Count active freelancers (is_active = 1)
        $activeFreelancers = \App\Models\Freelance::where('is_active', 1)->count();

        $stats = [
            'totalAgents' => Agent::count(),
            'activeAgents' => $activeAgents,
            'totalAffiliates' => Affiliate::count(),
            'activeAffiliates' => $activeAffiliates,
            'totalFreelancers' => \App\Models\Freelance::count(),
            'activeFreelancers' => $activeFreelancers,
            'revenueMTD' => $revenueMTD ?? 0,
            'revenueYTD' => $revenueYTD ?? 0,
            'pendingWithdrawals' => \App\Models\Withdraw::where('status', 'tertunda')->count(),
            'pendingClaims' => 0, // TODO: Implement when RewardClaim model is ready
        ];

        return view('admin.dashboard', compact('stats'));
    }

    public function users()
    {
        // Get all affiliates
        $affiliates = Affiliate::all()->map(function ($affiliate) {
            return [
                'id' => $affiliate->id,
                'name' => $affiliate->nama,
                'email' => $affiliate->email,
                'phone' => $affiliate->no_wa,
                'role' => 'affiliate',
                'status' => $affiliate->is_active ? 'active' : 'reject',
                'created_at' => $affiliate->date_register ?? $affiliate->created_at,
                'referral_code' => $affiliate->link_referral,
                'province' => $affiliate->provinsi,
                'city' => $affiliate->kab_kota,
                'address' => $affiliate->alamat_lengkap,
            ];
        });

        // Get all agents (travel agents)
        $agents = Agent::with(['affiliate', 'freelance'])->get()->map(function ($agent) {
            return [
                'id' => $agent->id,
                'name' => $agent->nama_pic,
                'email' => $agent->email,
                'phone' => $agent->no_hp,
                'role' => 'agent',
                'status' => $agent->status === 'approve' ? 'active' : ($agent->status === 'reject' ? 'reject' : 'pending'),
                'created_at' => $agent->created_at,
                'referral_code' => $agent->link_referal,
                'agent_category' => $agent->kategori_agent,
                'travel_name' => $agent->nama_travel,
                'travel_type' => $agent->jenis_travel,
                'province' => $agent->provinsi,
                'city' => $agent->kabupaten_kota,
                'address' => $agent->alamat_lengkap,
                'latitude' => $agent->lat,
                'longitude' => $agent->long,
                'logo' => $agent->logo,
                'ppiu' => $agent->surat_ppiu,
                'monthly_travellers' => $agent->total_traveller,
                'parent_type' => $agent->affiliate_id ? 'affiliate' : 'freelance',
                'parent_id' => $agent->affiliate_id ?? $agent->freelance_id,
            ];
        });

        // Get all freelances
        $freelances = Freelance::all()->map(function ($freelance) {
            return [
                'id' => $freelance->id,
                'name' => $freelance->nama,
                'email' => $freelance->email,
                'phone' => $freelance->no_wa,
                'role' => 'freelance',
                'status' => $freelance->is_active ? 'active' : 'reject',
                'created_at' => $freelance->date_register ?? $freelance->created_at,
                'referral_code' => $freelance->link_referral,
                'province' => $freelance->provinsi,
                'city' => $freelance->kab_kota,
                'address' => $freelance->alamat_lengkap,
            ];
        });

        // Combine all users
        $users = collect()
            ->merge($affiliates)
            ->merge($agents)
            ->merge($freelances)
            ->sortByDesc('created_at')
            ->values()
            ->all();

        $stats = [
            'affiliates' => Affiliate::count(),
            'affiliatesActive' => Affiliate::where('is_active', true)->count(),
            'affiliatesBanned' => Affiliate::where('is_active', false)->count(),
            'agents' => Agent::count(),
            'agentsActive' => Agent::where('status', 'approve')->where('is_active', 1)->count(),
            'agentsBanned' => Agent::where('is_active', 0)->count(),
            'freelance' => Freelance::count(),
            'freelanceActive' => Freelance::where('is_active', true)->count(),
            'freelanceBanned' => Freelance::where('is_active', false)->count(),
        ];

        return view('admin.users', compact('users', 'stats'));
    }

    public function packages()
    {
        $packages = DB::table('produk')->get();
        $margins = DB::table('margin')
            ->leftJoin('agents', 'margin.agent_id', '=', 'agents.id')
            ->leftJoin('affiliates', 'margin.affiliate_id', '=', 'affiliates.id')
            ->leftJoin('freelances', 'margin.freelance_id', '=', 'freelances.id')
            ->leftJoin('produk', 'margin.produk_id', '=', 'produk.id')
            ->select(
                'margin.*',
                'agents.nama_pic as agent_name',
                'affiliates.nama as affiliate_name',
                'freelances.nama as freelance_name',
                'produk.nama_paket as produk_name'
            )
            ->get();
        
        $agents = Agent::select('id', 'nama_pic as nama')->get();
        $affiliates = Affiliate::select('id', 'nama')->get();
        $freelances = Freelance::select('id', 'nama')->get();
        $products = DB::table('produk')->select('id', 'nama_paket')->get();
        
        return view('admin.packages', compact('packages', 'margins', 'agents', 'affiliates', 'freelances', 'products'));
    }

    public function storeMargin(Request $request)
    {
        try {
            $validated = $request->validate([
                'produk_id' => 'required|exists:produk,id',
                'harga_eup' => 'required|numeric',
                'persentase_margin_star' => 'required|numeric',
                'margin_star' => 'required|numeric',
                'margin_total' => 'required|numeric',
                'fee_travel' => 'required|numeric',
                'persentase_fee_travel' => 'required|numeric',
                'persentase_fee_affiliate' => 'required|numeric',
                'fee_affiliate' => 'required|numeric',
                'persentase_fee_host' => 'required|numeric',
                'fee_host' => 'required|numeric',
                'harga_tp_travel' => 'required|numeric',
                'harga_tp_host' => 'required|numeric',
                'poin' => 'required|numeric',
                'profit' => 'required|numeric',
            ]);

            $margin = DB::table('margin')->insertGetId([
                'agent_id' => $request->agent_id,
                'affiliate_id' => $request->affiliate_id,
                'freelance_id' => $request->freelance_id,
                'produk_id' => $validated['produk_id'],
                'harga_eup' => $validated['harga_eup'],
                'persentase_margin_star' => $validated['persentase_margin_star'],
                'margin_star' => $validated['margin_star'],
                'margin_total' => $validated['margin_total'],
                'fee_travel' => $validated['fee_travel'],
                'persentase_fee_travel' => $validated['persentase_fee_travel'],
                'persentase_fee_affiliate' => $validated['persentase_fee_affiliate'],
                'fee_affiliate' => $validated['fee_affiliate'],
                'persentase_fee_host' => $validated['persentase_fee_host'],
                'fee_host' => $validated['fee_host'],
                'harga_tp_travel' => $validated['harga_tp_travel'],
                'harga_tp_host' => $validated['harga_tp_host'],
                'poin' => $validated['poin'],
                'profit' => $validated['profit'],
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            $newMargin = DB::table('margin')
                ->leftJoin('agents', 'margin.agent_id', '=', 'agents.id')
                ->leftJoin('affiliates', 'margin.affiliate_id', '=', 'affiliates.id')
                ->leftJoin('freelances', 'margin.freelance_id', '=', 'freelances.id')
                ->leftJoin('produk', 'margin.produk_id', '=', 'produk.id')
                ->select(
                    'margin.*',
                    'agents.nama_pic as agent_name',
                    'affiliates.nama as affiliate_name',
                    'freelances.nama as freelance_name',
                    'produk.nama_paket as produk_name'
                )
                ->where('margin.id', $margin)
                ->first();

            return response()->json($newMargin, 201);
        } catch (\Exception $e) {
            \Log::error('Error creating margin: ' . $e->getMessage());
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function updateMargin(Request $request, $id)
    {
        try {
            $validated = $request->validate([
                'produk_id' => 'required|exists:produk,id',
                'harga_eup' => 'required|numeric',
                'persentase_margin_star' => 'required|numeric',
                'margin_star' => 'required|numeric',
                'margin_total' => 'required|numeric',
                'fee_travel' => 'required|numeric',
                'persentase_fee_travel' => 'required|numeric',
                'persentase_fee_affiliate' => 'required|numeric',
                'fee_affiliate' => 'required|numeric',
                'persentase_fee_host' => 'required|numeric',
                'fee_host' => 'required|numeric',
                'harga_tp_travel' => 'required|numeric',
                'harga_tp_host' => 'required|numeric',
                'poin' => 'required|numeric',
                'profit' => 'required|numeric',
            ]);

            DB::table('margin')->where('id', $id)->update([
                'agent_id' => $request->agent_id,
                'affiliate_id' => $request->affiliate_id,
                'freelance_id' => $request->freelance_id,
                'produk_id' => $validated['produk_id'],
                'harga_eup' => $validated['harga_eup'],
                'persentase_margin_star' => $validated['persentase_margin_star'],
                'margin_star' => $validated['margin_star'],
                'margin_total' => $validated['margin_total'],
                'fee_travel' => $validated['fee_travel'],
                'persentase_fee_travel' => $validated['persentase_fee_travel'],
                'persentase_fee_affiliate' => $validated['persentase_fee_affiliate'],
                'fee_affiliate' => $validated['fee_affiliate'],
                'persentase_fee_host' => $validated['persentase_fee_host'],
                'fee_host' => $validated['fee_host'],
                'harga_tp_travel' => $validated['harga_tp_travel'],
                'harga_tp_host' => $validated['harga_tp_host'],
                'poin' => $validated['poin'],
                'profit' => $validated['profit'],
                'updated_at' => now(),
            ]);

            return response()->json(['success' => true], 200);
        } catch (\Exception $e) {
            \Log::error('Error updating margin: ' . $e->getMessage());
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function deleteMargin($id)
    {
        try {
            DB::table('margin')->where('id', $id)->delete();
            return response()->json(['success' => true], 200);
        } catch (\Exception $e) {
            \Log::error('Error deleting margin: ' . $e->getMessage());
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function orders()
    {
        $packages = \App\Models\Produk::orderBy('provider', 'asc')
            ->get()
            ->map(function ($p) {
                return [
                    'id' => $p->id,
                    'name' => $p->nama_paket,
                    'provider' => $p->provider,
                    'price' => (int) $p->harga_tp_travel,
                    'sellPrice' => (int) $p->harga_tp_travel,
                    'masa_aktif' => $p->masa_aktif,
                ];
            });

        return view('admin.order', [
            'packages' => $packages
        ]);
    }

    public function transactions()
    {
        // Ambil semua pembayaran dengan relasi - pastikan pesanan di-load berdasarkan batch_id
        $pembayaranData = \App\Models\Pembayaran::with([
                'agent.affiliate', 
                'produk', 
                'pesanan' => function($query) {
                    // Tidak perlu kondisi tambahan, relasi sudah via batch_id
                }
            ])
            ->orderBy('created_at', 'desc')
            ->get();

        // Group by batch_id untuk menggabungkan batch yang sama
        $transactions = $pembayaranData->groupBy('batch_id')->map(function($batchGroup) {
            // Ambil pembayaran pertama sebagai representasi batch
            $pembayaran = $batchGroup->first();
            
            // Gabungkan semua pesanan dari semua pembayaran dengan batch_id yang sama
            $items = $batchGroup->flatMap(function($p) {
                return $p->pesanan->map(function($pesanan) use ($p) {
                    return [
                        'msisdn' => $pesanan->msisdn,
                        'provider' => $this->detectProvider($pesanan->msisdn),
                        'packageName' => $pesanan->nama_paket ?? $p->produk->nama_paket ?? 'N/A',
                        'status' => $pesanan->status_aktivasi ?? 'proses',
                        'price' => $pesanan->harga_jual ?? 0,
                        'margin' => $pesanan->profit ?? 0,
                    ];
                });
            })->values()->toArray();

            // Calculate total amount dan margin untuk batch yang di-merge
            $totalAmount = $batchGroup->sum('total_pembayaran');
            $totalMargin = $batchGroup->sum('profit');

            // Calculate batch status
            $batchStatus = $this->calculateBatchStatus($pembayaran, $items);

            return [
                'id' => $pembayaran->id,
                'batchId' => $pembayaran->batch_id,
                'batchName' => $pembayaran->nama_batch ?? 'Batch ' . $pembayaran->batch_id,
                'status' => $batchStatus,
                'items' => $items,
                'createdAt' => $pembayaran->created_at->toISOString(),
                'travelName' => $pembayaran->agent->nama_travel ?? $pembayaran->agent->nama_pic ?? 'N/A',
                'territory' => ($pembayaran->agent->provinsi && $pembayaran->agent->kabupaten_kota) 
                    ? $pembayaran->agent->kabupaten_kota . ', ' . $pembayaran->agent->provinsi
                    : ($pembayaran->agent->kabupaten_kota ?? $pembayaran->agent->provinsi ?? null),
                'agentName' => $pembayaran->agent->nama_pic ?? null,
                'agentPhone' => $pembayaran->agent->no_hp ?? null,
                'affiliateName' => $pembayaran->agent->affiliate->nama ?? null,
                'affiliatePhone' => $pembayaran->agent->affiliate->no_wa ?? null,
                'totalAmount' => $totalAmount,
                'marginTotal' => $totalMargin,
            ];
        })->values();
        
        return view('admin.transactions', compact('transactions'));
    }

    private function detectProvider($phoneNumber)
    {
        $firstDigits = substr($phoneNumber, 0, 4);
        
        // Telkomsel
        if (in_array(substr($firstDigits, 0, 4), ['0811', '0812', '0813', '0821', '0822', '0823', '0851', '0852', '0853'])) {
            return 'Telkomsel';
        }
        // Indosat
        if (in_array(substr($firstDigits, 0, 4), ['0814', '0815', '0816', '0855', '0856', '0857', '0858'])) {
            return 'Indosat';
        }
        // XL
        if (in_array(substr($firstDigits, 0, 4), ['0817', '0818', '0819', '0859', '0877', '0878'])) {
            return 'XL';
        }
        // Tri
        if (in_array(substr($firstDigits, 0, 4), ['0895', '0896', '0897', '0898', '0899'])) {
            return 'Tri';
        }
        // Smartfren
        if (in_array(substr($firstDigits, 0, 4), ['0881', '0882', '0883', '0884', '0885', '0886', '0887', '0888', '0889'])) {
            return 'Smartfren';
        }
        
        return 'Unknown';
    }

    private function calculateBatchStatus($pembayaran, $items)
    {
        // Jika pembayaran WAITING atau belum dibayar
        if (in_array(strtolower($pembayaran->status_pembayaran), ['waiting', 'menunggu'])) {
            return 'pending';
        }
        
        // Jika pembayaran SUCCESS/VERIFY/berhasil/paid, cek status items aktivasi
        if (in_array(strtolower($pembayaran->status_pembayaran), ['success', 'verify', 'paid', 'berhasil', 'selesai'])) {
            if (empty($items)) {
                return 'processing';
            }

            $statuses = collect($items)->pluck('status');
            $totalItems = $statuses->count();
            
            // Hitung jumlah setiap status
            $berhasilCount = $statuses->filter(fn($s) => in_array(strtolower($s), ['berhasil', 'selesai', 'completed', 'success']))->count();
            $prosesCount = $statuses->filter(fn($s) => in_array(strtolower($s), ['proses', 'processing', 'pending']))->count();
            $gagalCount = $statuses->filter(fn($s) => in_array(strtolower($s), ['gagal', 'failed']))->count();
            
            // Jika semua berhasil
            if ($berhasilCount === $totalItems) {
                return 'completed';
            }
            
            // Jika ada yang berhasil (mixed)
            if ($berhasilCount > 0) {
                return 'processing';
            }
            
            // Jika semua gagal
            if ($gagalCount === $totalItems) {
                return 'failed';
            }
            
            // Jika masih proses semua atau mixed
            return 'processing';
        }
        
        // Jika status pembayaran FAILED/EXPIRED/cancelled
        if (in_array(strtolower($pembayaran->status_pembayaran), ['failed', 'expired', 'gagal', 'cancelled', 'batal'])) {
            return 'failed';
        }
        
        return 'pending';
    }

    public function withdrawals()
    {
        $withdrawals = \App\Models\Withdraw::with(['agent', 'rekening'])
            ->orderBy('created_at', 'desc')
            ->get()
            ->map(function($withdraw) {
                return [
                    'id' => $withdraw->id,
                    'user_name' => $withdraw->agent->nama_pic ?? 'N/A',
                    'user_email' => $withdraw->agent->email ?? 'N/A',
                    'amount' => $withdraw->jumlah,
                    'bank_name' => $withdraw->rekening->bank ?? 'N/A',
                    'account_number' => $withdraw->rekening->nomor_rekening ?? 'N/A',
                    'account_name' => $withdraw->rekening->nama_rekening ?? 'N/A',
                    'keterangan' => $withdraw->keterangan,
                    'status' => $withdraw->status,
                    'created_at' => $withdraw->created_at,
                    'date_approve' => $withdraw->date_approve,
                ];
            });
        
        return view('admin.withdrawals', compact('withdrawals'));
    }

    public function approveWithdrawal($id)
    {
        try {
            $withdraw = \App\Models\Withdraw::with('agent')->findOrFail($id);
            
            if ($withdraw->status !== 'pending') {
                return response()->json([
                    'success' => false,
                    'message' => 'Withdrawal ini sudah diproses sebelumnya'
                ], 400);
            }
            
            $agent = $withdraw->agent;
            
            if ($agent->saldo < $withdraw->jumlah) {
                return response()->json([
                    'success' => false,
                    'message' => 'Saldo agent tidak mencukupi'
                ], 400);
            }
            
            \DB::beginTransaction();
            
            $withdraw->update([
                'status' => 'approve',
                'date_approve' => now()->format('Y-m-d')
            ]);
            
            $agent->decrement('saldo', $withdraw->jumlah);
            
            \DB::commit();
            
            return response()->json([
                'success' => true,
                'message' => 'Withdrawal berhasil diapprove'
            ]);
        } catch (\Exception $e) {
            \DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Gagal approve withdrawal: ' . $e->getMessage()
            ], 500);
        }
    }

    public function rejectWithdrawal(Request $request, $id)
    {
        try {
            $request->validate([
                'alasan_reject' => 'required|string|min:10'
            ], [
                'alasan_reject.required' => 'Alasan penolakan harus diisi',
                'alasan_reject.min' => 'Alasan penolakan minimal 10 karakter'
            ]);
            
            $withdraw = \App\Models\Withdraw::findOrFail($id);
            
            if ($withdraw->status !== 'pending') {
                return response()->json([
                    'success' => false,
                    'message' => 'Withdrawal ini sudah diproses sebelumnya'
                ], 400);
            }
            
            $withdraw->update([
                'status' => 'reject',
                'alasan_reject' => $request->alasan_reject
            ]);
            
            return response()->json([
                'success' => true,
                'message' => 'Withdrawal berhasil direject'
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => $e->errors()['alasan_reject'][0] ?? 'Validasi gagal'
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal reject withdrawal: ' . $e->getMessage()
            ], 500);
        }
    }

    public function rewards()
    {
        // TODO: Implement when Reward model is ready
        $rewards = [];
        
        return view('admin.rewards', compact('rewards'));
    }

    public function rewardClaims()
    {
        // TODO: Implement when RewardClaim model is ready
        $claims = [];
        
        return view('admin.reward-claims', compact('claims'));
    }

    public function analytics()
    {
        // $stats = [
        //     'totalRevenue' => 0, // TODO: Calculate from transactions
        //     'totalOrders' => 0, // TODO: Count from orders
        //     'activeUsers' => User::where('status', 'active')->count(),
        //     'conversionRate' => 0, // TODO: Calculate conversion
        // ];

        $recentActivity = []; // TODO: Implement activity log
        
        return view('admin.analytics');
    }

    public function profile()
    {
        return view('admin.profile');
    }

    public function toggleUserStatus(Request $request, $id)
    {
        // Determine which model to use based on request
        $role = $request->input('role', 'agent'); // default to agent if not specified
        
        switch ($role) {
            case 'affiliate':
                $user = Affiliate::findOrFail($id);
                $user->is_active = !$user->is_active;
                $user->save();
                $status = $user->is_active ? 'active' : 'reject';
                break;
                
            case 'freelance':
                $user = Freelance::findOrFail($id);
                $user->is_active = !$user->is_active;
                $user->save();
                $status = $user->is_active ? 'active' : 'reject';
                break;
                
            case 'agent':
            default:
                $user = Agent::findOrFail($id);
                // Toggle between approve and reject
                if ($user->status === 'approve') {
                    $user->status = 'reject';
                    $user->is_active = 0;
                } else {
                    $user->status = 'approve';
                    $user->is_active = 1;
                    if (!$user->date_approve) {
                        $user->date_approve = now()->format('Y-m-d');
                    }
                }
                $user->save();
                $status = $user->status === 'approve' ? 'active' : 'reject';
                break;
        }

        return response()->json([
            'success' => true,
            'status' => $status
        ]);
    }

    // ========== AFFILIATE METHODS ==========

    /**
     * Display a listing of affiliates
     */
    public function indexAffiliates()
    {
        $affiliates = Affiliate::with('agents')->paginate(10);
        return view('admin.affiliates.index', compact('affiliates'));
    }

    /**
     * Show the form for creating a new affiliate
     */
    public function createAffiliate()
    {
        return view('admin.affiliates.create');
    }

    /**
     * Store a newly created affiliate in storage
     */
    public function storeAffiliate(Request $request)
    {
        $request->merge([
            'no_wa' => $this->normalizeIndonesianMsisdn((string) $request->input('no_wa', '')),
        ]);

        $validator = Validator::make($request->all(), [
            'nama' => 'required|string|max:255',
            'email' => 'required|email|unique:affiliates,email',
            'no_wa' => 'required|string|unique:affiliates,no_wa',
            'provinsi' => 'required|string',
            'kab_kota' => 'required|string',
            'alamat_lengkap' => 'required|string',
            'link_referral' => 'required|string|alpha_dash:ascii|unique:affiliates,link_referral',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        try {
            Affiliate::create([
                'nama' => $request->nama,
                'email' => $request->email,
                'no_wa' => $request->no_wa,
                'provinsi' => $request->provinsi,
                'kab_kota' => $request->kab_kota,
                'alamat_lengkap' => $request->alamat_lengkap,
                'link_referral' => $request->link_referral,
                'date_register' => now()->format('Y-m-d'),
                'is_active' => true,
            ]);

            return $this->redirectBackTo('admin.affiliates.index', $request)->with('success', 'Affiliate berhasil ditambahkan');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage())->withInput();
        }
    }

    /**
     * Display the specified affiliate
     */
    public function showAffiliate($id)
    {
        $affiliate = Affiliate::with('agents')->findOrFail($id);
        return view('admin.affiliates.show', compact('affiliate'));
    }

    /**
     * Show the form for editing the specified affiliate
     */
    public function editAffiliate($id)
    {
        $affiliate = Affiliate::findOrFail($id);
        return view('admin.affiliates.edit', compact('affiliate'));
    }

    /**
     * Update the specified affiliate in storage
     */
    public function updateAffiliate(Request $request, $id)
    {
        $affiliate = Affiliate::findOrFail($id);

        $validator = Validator::make($request->all(), [
            'nama' => 'required|string|max:255',
            'email' => 'required|email|unique:affiliates,email,' . $id,
            'no_wa' => 'required|string|unique:affiliates,no_wa,' . $id,
            'provinsi' => 'required|string',
            'kab_kota' => 'required|string',
            'alamat_lengkap' => 'required|string',
            'link_referral' => 'required|string|alpha_dash:ascii|unique:affiliates,link_referral,' . $id,
            'is_active' => 'boolean',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        try {
            $affiliate->update($request->all());
            return redirect()->route('admin.affiliates.index')->with('success', 'Affiliate berhasil diperbarui');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage())->withInput();
        }
    }

    /**
     * Remove the specified affiliate from storage
     */
    public function destroyAffiliate($id)
    {
        try {
            $affiliate = Affiliate::findOrFail($id);
            $affiliate->delete();
            return redirect()->route('admin.affiliates.index')->with('success', 'Affiliate berhasil dihapus');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    /**
     * Activate affiliate
     */
    public function activateAffiliate($id, Request $request)
    {
        try {
            $affiliate = Affiliate::findOrFail($id);
            $affiliate->update(['is_active' => true]);
            if ($request->wantsJson()) {
                return response()->json(['success' => true, 'message' => 'Affiliate berhasil diaktifkan']);
            }
            return redirect()->back()->with('success', 'Affiliate berhasil diaktifkan');
        } catch (\Exception $e) {
            if ($request->wantsJson()) {
                return response()->json(['success' => false, 'message' => 'Terjadi kesalahan: ' . $e->getMessage()], 500);
            }
            return redirect()->back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    /**
     * Deactivate affiliate
     */
    public function deactivateAffiliate($id, Request $request)
    {
        try {
            $affiliate = Affiliate::findOrFail($id);
            $affiliate->update(['is_active' => false]);
            if ($request->wantsJson()) {
                return response()->json(['success' => true, 'message' => 'Affiliate berhasil dinonaktifkan']);
            }
            return redirect()->back()->with('success', 'Affiliate berhasil dinonaktifkan');
        } catch (\Exception $e) {
            if ($request->wantsJson()) {
                return response()->json(['success' => false, 'message' => 'Terjadi kesalahan: ' . $e->getMessage()], 500);
            }
            return redirect()->back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    // ========== FREELANCE METHODS ==========

    /**
     * Display a listing of freelances
     */
    public function indexFreelances()
    {
        $freelances = Freelance::with('agents')->paginate(10);
        return view('admin.freelances.index', compact('freelances'));
    }

    /**
     * Show the form for creating a new freelance
     */
    public function createFreelance()
    {
        return view('admin.freelances.create');
    }

    /**
     * Store a newly created freelance in storage
     */
    public function storeFreelance(Request $request)
    {
        $request->merge([
            'no_wa' => $this->normalizeIndonesianMsisdn((string) $request->input('no_wa', '')),
        ]);

        $validator = Validator::make($request->all(), [
            'nama' => 'required|string|max:255',
            'email' => 'required|email|unique:freelances,email',
            'no_wa' => 'required|string|unique:freelances,no_wa',
            'provinsi' => 'required|string',
            'kab_kota' => 'required|string',
            'alamat_lengkap' => 'required|string',
            'link_referral' => 'required|string|alpha_dash:ascii|unique:freelances,link_referral',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        try {
            Freelance::create([
                'nama' => $request->nama,
                'email' => $request->email,
                'no_wa' => $request->no_wa,
                'provinsi' => $request->provinsi,
                'kab_kota' => $request->kab_kota,
                'alamat_lengkap' => $request->alamat_lengkap,
                'link_referral' => $request->link_referral,
                'date_register' => now()->format('Y-m-d'),
                'is_active' => true,
            ]);

            return $this->redirectBackTo('admin.freelances.index', $request)->with('success', 'Freelance berhasil ditambahkan');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage())->withInput();
        }
    }

    /**
     * Display the specified freelance
     */
    public function showFreelance($id)
    {
        $freelance = Freelance::with('agents')->findOrFail($id);
        return view('admin.freelances.show', compact('freelance'));
    }

    /**
     * Show the form for editing the specified freelance
     */
    public function editFreelance($id)
    {
        $freelance = Freelance::findOrFail($id);
        return view('admin.freelances.edit', compact('freelance'));
    }

    /**
     * Update the specified freelance in storage
     */
    public function updateFreelance(Request $request, $id)
    {
        $freelance = Freelance::findOrFail($id);

        $validator = Validator::make($request->all(), [
            'nama' => 'required|string|max:255',
            'email' => 'required|email|unique:freelances,email,' . $id,
            'no_wa' => 'required|string|unique:freelances,no_wa,' . $id,
            'provinsi' => 'required|string',
            'kab_kota' => 'required|string',
            'alamat_lengkap' => 'required|string',
            'link_referral' => 'required|string|alpha_dash:ascii|unique:freelances,link_referral,' . $id,
            'is_active' => 'boolean',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        try {
            $freelance->update($request->all());
            return redirect()->route('admin.freelances.index')->with('success', 'Freelance berhasil diperbarui');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage())->withInput();
        }
    }

    /**
     * Remove the specified freelance from storage
     */
    public function destroyFreelance($id)
    {
        try {
            $freelance = Freelance::findOrFail($id);
            $freelance->delete();
            return redirect()->route('admin.freelances.index')->with('success', 'Freelance berhasil dihapus');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    /**
     * Activate freelance
     */
    public function activateFreelance($id, Request $request)
    {
        try {
            $freelance = Freelance::findOrFail($id);
            $freelance->update(['is_active' => true]);
            if ($request->wantsJson()) {
                return response()->json(['success' => true, 'message' => 'Freelance berhasil diaktifkan']);
            }
            return redirect()->back()->with('success', 'Freelance berhasil diaktifkan');
        } catch (\Exception $e) {
            if ($request->wantsJson()) {
                return response()->json(['success' => false, 'message' => 'Terjadi kesalahan: ' . $e->getMessage()], 500);
            }
            return redirect()->back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    /**
     * Deactivate freelance
     */
    public function deactivateFreelance($id, Request $request)
    {
        try {
            $freelance = Freelance::findOrFail($id);
            $freelance->update(['is_active' => false]);
            if ($request->wantsJson()) {
                return response()->json(['success' => true, 'message' => 'Freelance berhasil dinonaktifkan']);
            }
            return redirect()->back()->with('success', 'Freelance berhasil dinonaktifkan');
        } catch (\Exception $e) {
            if ($request->wantsJson()) {
                return response()->json(['success' => false, 'message' => 'Terjadi kesalahan: ' . $e->getMessage()], 500);
            }
            return redirect()->back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    // ========== AGENT METHODS ==========

    /**
     * Display a listing of agents
     */
    public function indexAgents()
    {
        $agents = Agent::paginate(10);
        return view('admin.agents.index', compact('agents'));
    }

    /**
     * Show the form for creating a new agent
     */
    public function createAgent()
    {
        return view('admin.agents.create');
    }

    /**
     * Store a newly created agent in storage
     */
    public function storeAgent(Request $request)
    {
        $request->merge([
            'no_hp' => $this->normalizeIndonesianMsisdn((string) $request->input('no_hp', '')),
        ]);

        $validator = Validator::make($request->all(), [
            'email' => 'required|email|unique:agents,email',
            'nama_pic' => 'required|string|max:255',
            'no_hp' => 'required|string|unique:agents,no_hp',
            'nama_travel' => 'required|string|max:255',
            'jenis_travel' => 'required|string|max:100',
            'total_traveller' => 'required|integer|min:0',
            'kategori_agent' => 'required|in:Referral,Host',
            'provinsi' => 'required|string|max:100',
            'kabupaten_kota' => 'required|string|max:100',
            'alamat_lengkap' => 'required|string',
            'affiliate_id' => 'nullable|integer|exists:affiliates,id',
            'freelance_id' => 'nullable|integer|exists:freelances,id',
            'latitude' => 'nullable|numeric',
            'longitude' => 'nullable|numeric',
            'logo' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        if ($request->filled('affiliate_id') && $request->filled('freelance_id')) {
            return redirect()->back()->withErrors(['downline' => 'Pilih salah satu downline (affiliate atau freelance).'])->withInput();
        }

        try {
            $data = [
                'email' => $request->email,
                'affiliate_id' => $request->affiliate_id,
                'freelance_id' => $request->freelance_id,
                'kategori_agent' => $request->kategori_agent,
                'nama_pic' => $request->nama_pic,
                'no_hp' => $request->no_hp,
                'nama_travel' => $request->nama_travel,
                'jenis_travel' => $request->jenis_travel,
                'total_traveller' => $request->total_traveller,
                'provinsi' => $request->provinsi,
                'kabupaten_kota' => $request->kabupaten_kota,
                'alamat_lengkap' => $request->alamat_lengkap,
                'lat' => $request->latitude,
                'long' => $request->longitude,
                'status' => 'pending',
                'is_active' => 0,
            ];

            // Handle logo upload
            if ($request->hasFile('logo')) {
                $logoPath = $request->file('logo')->store('agent_logos', 'public');
                $data['logo'] = $logoPath;
            }

            Agent::create($data);

            return $this->redirectBackTo('admin.agents.index', $request)->with('success', 'Travel Agent berhasil ditambahkan');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage())->withInput();
        }
    }

    /**
     * Display the specified agent
     */
    public function showAgent($id)
    {
        $agent = Agent::findOrFail($id);
        return view('admin.agents.show', compact('agent'));
    }

    /**
     * Show the form for editing the specified agent
     */
    public function editAgent($id)
    {
        $agent = Agent::findOrFail($id);
        return view('admin.agents.edit', compact('agent'));
    }

    /**
     * Update the specified agent in storage
     */
    public function updateAgent(Request $request, $id)
    {
        $agent = Agent::findOrFail($id);

        $validator = Validator::make($request->all(), [
            'email' => 'required|email|unique:agents,email,' . $id,
            'nama_pic' => 'required|string|max:255',
            'no_hp' => 'required|string|unique:agents,no_hp,' . $id,
            'nama_travel' => 'required|string|max:255',
            'jenis_travel' => 'required|string|max:100',
            'total_traveller' => 'required|integer|min:0',
            'kategori_agent' => 'required|in:Referral,Host',
            'provinsi' => 'required|string|max:100',
            'kabupaten_kota' => 'required|string|max:100',
            'alamat_lengkap' => 'required|string',
            'status' => 'required|in:pending,approve,reject',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        try {
            $payload = $request->only([
                'email',
                'nama_pic',
                'no_hp',
                'nama_travel',
                'jenis_travel',
                'total_traveller',
                'kategori_agent',
                'provinsi',
                'kabupaten_kota',
                'alamat_lengkap',
                'status',
            ]);
            $payload['no_hp'] = $this->normalizeIndonesianMsisdn((string) $payload['no_hp']);
            $agent->update($payload);
            return redirect()->route('admin.agents.index')->with('success', 'Agent berhasil diperbarui');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage())->withInput();
        }
    }

    /**
     * Remove the specified agent from storage
     */
    public function destroyAgent($id)
    {
        try {
            $agent = Agent::findOrFail($id);
            $agent->delete();
            return redirect()->route('admin.agents.index')->with('success', 'Agent berhasil dihapus');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    /**
     * Approve agent
     */
    public function approveAgent(Request $request, $id)
    {
        try {
            $agent = Agent::findOrFail($id);
            
            // Validasi link_referal wajib diisi
            $linkReferal = $request->input('link_referal');
            
            if (empty($linkReferal)) {
                if ($request->wantsJson()) {
                    return response()->json([
                        'success' => false, 
                        'message' => 'Validasi gagal',
                        'errors' => ['link_referal' => ['Link referral wajib diisi']]
                    ], 422);
                }
                return redirect()->back()->with('error', 'Link referral wajib diisi');
            }
            
            // Cek apakah link_referal sudah digunakan agent lain
            $existingAgent = Agent::where('link_referal', $linkReferal)
                ->where('id', '!=', $id)
                ->first();

            if ($existingAgent) {
                if ($request->wantsJson()) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Validasi gagal',
                        'errors' => ['link_referal' => ['Link referral sudah digunakan, mohon gunakan yang lain']]
                    ], 422);
                }
                return redirect()->back()->with('error', 'Link referral sudah digunakan');
            }

            $agent->link_referal = $linkReferal;
            $agent->status = 'approve';
            $agent->is_active = 1;
            if (!$agent->date_approve) {
                $agent->date_approve = now()->format('Y-m-d');
            }
            $agent->save();

            if ($request->wantsJson()) {
                return response()->json(['success' => true, 'message' => 'Agent berhasil disetujui']);
            }

            return redirect()->back()->with('success', 'Agent berhasil disetujui');
        } catch (\Exception $e) {
            if ($request->wantsJson()) {
                return response()->json(['success' => false, 'message' => 'Terjadi kesalahan: ' . $e->getMessage()], 500);
            }
            return redirect()->back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    /**
     * Reject agent
     */
    public function rejectAgent(Request $request, $id)
    {
        try {
            $agent = Agent::findOrFail($id);
            $agent->status = 'reject';
            $agent->is_active = 0;
            $agent->save();

            if ($request->wantsJson()) {
                return response()->json(['success' => true, 'message' => 'Agent berhasil ditolak']);
            }

            return redirect()->back()->with('success', 'Agent berhasil ditolak');
        } catch (\Exception $e) {
            if ($request->wantsJson()) {
                return response()->json(['success' => false, 'message' => 'Terjadi kesalahan: ' . $e->getMessage()], 500);
            }
            return redirect()->back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    /**
     * Activate agent
     */
    public function activateAgent($id, Request $request)
    {
        try {
            $agent = Agent::findOrFail($id);
            $agent->update(['is_active' => true]);
            if ($request->wantsJson()) {
                return response()->json(['success' => true, 'message' => 'Agent berhasil diaktifkan']);
            }
            return redirect()->back()->with('success', 'Agent berhasil diaktifkan');
        } catch (\Exception $e) {
            if ($request->wantsJson()) {
                return response()->json(['success' => false, 'message' => 'Terjadi kesalahan: ' . $e->getMessage()], 500);
            }
            return redirect()->back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    /**
     * Deactivate agent
     */
    public function deactivateAgent($id, Request $request)
    {
        try {
            $agent = Agent::findOrFail($id);
            $agent->update(['is_active' => false]);
            if ($request->wantsJson()) {
                return response()->json(['success' => true, 'message' => 'Agent berhasil dinonaktifkan']);
            }
            return redirect()->back()->with('success', 'Agent berhasil dinonaktifkan');
        } catch (\Exception $e) {
            if ($request->wantsJson()) {
                return response()->json(['success' => false, 'message' => 'Terjadi kesalahan: ' . $e->getMessage()], 500);
            }
            return redirect()->back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }
}
