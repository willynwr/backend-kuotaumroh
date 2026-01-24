<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Agent;
use App\Models\Affiliate;
use App\Models\Freelance;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;

class AgentController extends Controller
{
    /**
     * Tampilkan halaman welcome (homepage) dengan default agent
     * Route: /
     */
    public function showWelcome()
    {
        // Cari agent dengan link_referal 'kuotaumroh' di database
        $defaultAgent = Agent::where('link_referal', 'kuotaumroh')->first();
        
        if ($defaultAgent) {
            // Gunakan agent dari database
            $agent = $defaultAgent;
        } else {
            // Fallback: Create a virtual agent object for default store
            // Gunakan ID 1 sebagai default ref_code
            $agent = (object) [
                'id' => 1, // ID agent default, sesuaikan dengan database Anda
                'nama_travel' => 'Kuotaumroh.id',
                'nama_pic' => 'Official Store',
                'email' => 'support@kuotaumroh.id',
                'no_hp' => '628112994499',
                'alamat_lengkap' => 'Indonesia',
                'logo' => null,
                'link_referal' => 'kuotaumroh',
                'is_active' => true,
            ];
        }

        return view('welcome', compact('agent'));
    }

    /**
     * Handle agent signup dengan referral link dari affiliate/freelance
     * Route: /agent/{link_referral}
     */
    public function signupWithReferral($linkReferral)
    {
        // Cek apakah link_referral milik affiliate
        $affiliate = Affiliate::where('link_referral', $linkReferral)
            ->where('is_active', true)
            ->first();

        if ($affiliate) {
            // Redirect ke login dengan affiliate referral
            return redirect()->route('login', [
                'ref' => 'affiliate:' . $affiliate->id,
                'referrer_name' => $affiliate->nama
            ]);
        }

        // Cek apakah link_referral milik freelance
        $freelance = Freelance::where('link_referral', $linkReferral)
            ->where('is_active', true)
            ->first();

        if ($freelance) {
            // Redirect ke login dengan freelance referral
            return redirect()->route('login', [
                'ref' => 'freelance:' . $freelance->id,
                'referrer_name' => $freelance->nama
            ]);
        }

        // Jika link_referral tidak valid, redirect ke login biasa
        return redirect()->route('login')->with('error', 'Link referral tidak valid atau sudah tidak aktif');
    }

    /**
     * Tampilkan halaman toko agent berdasarkan link_referral
     * Route: /u/{link_referal}
     */
    public function showStore($linkReferal)
    {
        // Handle default store "kuotaumroh"
        if ($linkReferal === 'kuotaumroh') {
            // Cari agent dengan link_referal 'kuotaumroh' di database
            $defaultAgent = Agent::where('link_referal', 'kuotaumroh')->first();
            
            if ($defaultAgent) {
                // Gunakan agent dari database
                $agent = $defaultAgent;
            } else {
                // Fallback: Create a virtual agent object for default store
                // Gunakan ID 1 sebagai default ref_code
                $agent = (object) [
                    'id' => 1, // ID agent default, sesuaikan dengan database Anda
                    'nama_travel' => 'Kuotaumroh.id',
                    'nama_pic' => 'Official Store',
                    'email' => 'support@kuotaumroh.id',
                    'no_hp' => '628112994499',
                    'alamat_lengkap' => 'Indonesia',
                    'logo' => null,
                    'link_referal' => 'kuotaumroh',
                    'is_active' => true,
                ];
            }

            return view('agent.store', compact('agent'));
        }

        // Cari agent berdasarkan link_referal
        $agent = Agent::where('link_referal', $linkReferal)
            ->where('is_active', true)
            ->first();

        // Jika agent tidak ditemukan, redirect ke home
        if (!$agent) {
            return redirect('/u/kuotaumroh')->with('error', 'Toko tidak ditemukan atau sudah tidak aktif');
        }

        // Tampilkan halaman toko agent
        return view('agent.store', compact('agent'));
    }

    /**
     * Tampilkan halaman signup form untuk agent
     * Bisa diakses tanpa referral (affiliate_id = 1 default)
     * Route: GET /signup
     */
    public function signup()
    {
        return view('auth.signup');
    }

    public function asset(string $file)
    {
        if (!preg_match('/\A[A-Za-z0-9_\-\.]+\z/', $file)) {
            abort(404);
        }

        if (!str_ends_with(strtolower($file), '.png')) {
            abort(404);
        }

        $path = resource_path('views/agent/agent123/' . $file);
        if (!is_file($path)) {
            abort(404);
        }

        return response()->file($path, [
            'Cache-Control' => 'public, max-age=604800',
        ]);
    }

    public function dashboard()
    {
        return view('agent.dashboard');
    }

    public function catalog()
    {
        $packages = \App\Models\Produk::orderBy('created_at', 'desc')->get();
        
        // Debug: Log jumlah packages
        \Log::info('Agent Catalog - Total packages: ' . $packages->count());
        
        return view('agent.catalog', compact('packages'));
    }

    public function history()
    {
        return view('agent.history');
    }

    public function order()
    {
        return view('agent.order');
    }

    public function checkout()
    {
        return view('agent.checkout');
    }

    public function wallet()
    {
        $user = auth()->user();
        $walletBalance = [
            'balance' => 0,
            'pendingWithdrawal' => 0
        ];
        
        // Jika user adalah agent, ambil saldo dari database
        if ($user instanceof \App\Models\Agent) {
            $walletBalance['balance'] = $user->saldo ?? 0;
            // TODO: Hitung pending withdrawal dari table withdraw
            $walletBalance['pendingWithdrawal'] = 0;
        }
        
        return view('agent.wallet', [
            'walletBalance' => $walletBalance
        ]);
    }

    public function historyProfit()
    {
        $user = auth()->user();
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
            $monthlyHistory = \App\Models\Pesanan::where('agent_id', $user->id)
                ->whereHas('pembayaran', function($query) {
                    $query->whereIn('status_pembayaran', ['selesai', 'berhasil']);
                })
                ->selectRaw('DATE_FORMAT(created_at, "%Y-%m") as month, SUM(profit) as total_profit, COUNT(*) as total_transactions')
                ->groupBy('month')
                ->orderBy('month', 'DESC')
                ->limit(12)
                ->get();
            
            // Untuk setiap bulan, ambil detail transaksinya dan restructure ke array
            $monthlyHistoryArray = [];
            foreach ($monthlyHistory as $monthData) {
                $details = \App\Models\Pesanan::where('agent_id', $user->id)
                    ->whereHas('pembayaran', function($query) {
                        $query->whereIn('status_pembayaran', ['selesai', 'berhasil']);
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
            $profitData['yearly_history'] = \App\Models\Pesanan::where('agent_id', $user->id)
                ->whereHas('pembayaran', function($query) {
                    $query->whereIn('status_pembayaran', ['selesai', 'berhasil']);
                })
                ->selectRaw('YEAR(created_at) as year, SUM(profit) as total_profit, COUNT(*) as total_transactions')
                ->groupBy('year')
                ->orderBy('year', 'DESC')
                ->get();
        }
        
        return view('agent.history-profit', [
            'profitData' => $profitData
        ]);
    }

    public function profile()
    {
        return view('agent.profile');
    }

    public function referrals()
    {
        return view('agent.referrals');
    }

    public function index()
    {
        $agents = Agent::with(['affiliate', 'freelance'])->get();

        return response()->json([
            'message' => 'Agents retrieved successfully',
            'data' => $agents
        ], 200);
    }

    public function show($id)
    {
        $agent = Agent::with(['affiliate', 'freelance'])->find($id);

        if (!$agent) {
            return response()->json(['message' => 'Agent not found'], 404);
        }

        return response()->json([
            'message' => 'Agent retrieved successfully',
            'data' => $agent
        ], 200);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email|unique:agents,email|unique:affiliates,email|unique:freelances,email',
            'affiliate_id' => 'nullable|exists:affiliates,id',
            'freelance_id' => 'nullable|exists:freelances,id',
            'kategori_agent' => 'required|in:Referral,Host',
            'nama_pic' => 'required|string',
            'no_hp' => 'required|string|unique:agents,no_hp',
            'nama_travel' => 'nullable|string',
            'jenis_travel' => 'nullable|string',
            'provinsi' => 'required|string',
            'kabupaten_kota' => 'required|string',
            'alamat_lengkap' => 'required|string',
            'link_gmaps' => 'nullable|string',
            'long' => 'nullable|numeric',
            'lat' => 'nullable|numeric',
            'link_referal' => 'nullable|string',
            'rekening_agent' => 'nullable|string',
            'date_approve' => 'nullable|date',
            // Max 5 MB to match frontend hint
            'logo' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:5120',
            'surat_ppiu' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:5120',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        // Validasi: hanya boleh salah satu dari affiliate_id atau freelance_id
        if ($request->affiliate_id && $request->freelance_id) {
            return response()->json([
                'message' => 'Agent hanya bisa terhubung ke Affiliate ATAU Freelance, tidak keduanya'
            ], 422);
        }

        if (!$request->affiliate_id && !$request->freelance_id) {
            $request->merge(['affiliate_id' => 1]);
        }

        if ((int) $request->affiliate_id === 1 && !Affiliate::query()->whereKey(1)->exists()) {
            return response()->json([
                'message' => 'Affiliate default tidak ditemukan (id=1)'
            ], 422);
        }

        $data = $request->except(['logo', 'surat_ppiu']);

        if ($request->hasFile('logo')) {
            $logoPath = $request->file('logo')->store('agent_logos', 'public');
            $data['logo'] = $logoPath;
        }

        if ($request->hasFile('surat_ppiu')) {
            $suratPath = $request->file('surat_ppiu')->store('agent_documents', 'public');
            $data['surat_ppiu'] = $suratPath;
        }

        $agent = Agent::create($data);

        return response()->json([
            'message' => 'Agent successfully created',
            'data' => $agent
        ], 201);
    }

    public function update(Request $request, $id)
    {
        $agent = Agent::find($id);

        if (!$agent) {
            return response()->json(['message' => 'Agent not found'], 404);
        }

        $validator = Validator::make($request->all(), [
            'email' => 'email|unique:agents,email,' . $id . '|unique:affiliates,email|unique:freelances,email',
            'affiliate_id' => 'nullable|exists:affiliates,id',
            'freelance_id' => 'nullable|exists:freelances,id',
            'kategori_agent' => 'in:Referral,Host',
            'nama_pic' => 'string',
            'no_hp' => 'string|unique:agents,no_hp,' . $id,
            'nama_travel' => 'nullable|string',
            'jenis_travel' => 'nullable|string',
            'provinsi' => 'string',
            'kabupaten_kota' => 'string',
            'alamat_lengkap' => 'string',
            'link_gmaps' => 'nullable|string',
            'long' => 'nullable|numeric',
            'lat' => 'nullable|numeric',
            'link_referal' => 'nullable|string',
            'rekening_agent' => 'nullable|string',
            'date_approve' => 'nullable|date',
            'logo' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'surat_ppiu' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:2048',
            'total_traveller' => 'nullable|integer',
            'status' => 'string',
            'is_active' => 'boolean',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $data = $request->except(['logo', 'surat_ppiu']);

        if ($request->hasFile('logo')) {
            if ($agent->logo && Storage::disk('public')->exists($agent->logo)) {
                Storage::disk('public')->delete($agent->logo);
            }
            $logoPath = $request->file('logo')->store('agent_logos', 'public');
            $data['logo'] = $logoPath;
        }

        if ($request->hasFile('surat_ppiu')) {
            if ($agent->surat_ppiu && Storage::disk('public')->exists($agent->surat_ppiu)) {
                Storage::disk('public')->delete($agent->surat_ppiu);
            }
            $suratPath = $request->file('surat_ppiu')->store('agent_documents', 'public');
            $data['surat_ppiu'] = $suratPath;
        }

        $agent->update($data);

        return response()->json([
            'message' => 'Agent successfully updated',
            'data' => $agent
        ], 200);
    }

    public function destroy($id)
    {
        $agent = Agent::find($id);

        if (!$agent) {
            return response()->json(['message' => 'Agent not found'], 404);
        }

        if ($agent->logo && Storage::disk('public')->exists($agent->logo)) {
            Storage::disk('public')->delete($agent->logo);
        }

        if ($agent->surat_ppiu && Storage::disk('public')->exists($agent->surat_ppiu)) {
            Storage::disk('public')->delete($agent->surat_ppiu);
        }

        $agent->delete();

        return response()->json(['message' => 'Agent successfully deleted'], 200);
    }

    public function approve(Request $request, $id)
    {
        $agent = Agent::find($id);

        if (!$agent) {
            return response()->json(['message' => 'Agent not found'], 404);
        }

        $validator = Validator::make($request->all(), [
            'link_referal' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $agent->update([
            'status' => 'approve',
            'link_referal' => $request->link_referal,
            'date_approve' => now()->format('Y-m-d'),
        ]);

        return response()->json([
            'message' => 'Agent successfully approved',
            'data' => $agent
        ], 200);
    }

    public function reject($id)
    {
        $agent = Agent::find($id);

        if (!$agent) {
            return response()->json(['message' => 'Agent not found'], 404);
        }

        $agent->update([
            'status' => 'reject',
        ]);

        return response()->json([
            'message' => 'Agent successfully rejected',
            'data' => $agent
        ], 200);
    }

    public function activate($id)
    {
        $agent = Agent::find($id);

        if (!$agent) {
            return response()->json(['message' => 'Agent not found'], 404);
        }

        $agent->update([
            'is_active' => 1,
        ]);

        return response()->json([
            'message' => 'Agent successfully activated',
            'data' => $agent
        ], 200);
    }

    public function deactivate($id)
    {
        $agent = Agent::find($id);

        if (!$agent) {
            return response()->json(['message' => 'Agent not found'], 404);
        }

        $agent->update([
            'is_active' => 0,
        ]);

        return response()->json([
            'message' => 'Agent successfully deactivated',
            'data' => $agent
        ], 200);
    }
}
