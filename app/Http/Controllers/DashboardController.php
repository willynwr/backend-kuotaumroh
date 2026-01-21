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
                ]
            ]);
        }

        // Jika bukan freelance, cek apakah milik agent
        $agent = Agent::where('link_referal', $linkReferral)
            ->where('is_active', true)
            ->first();

        if ($agent) {
            // Verify that authenticated user matches the dashboard owner
            if ($sessionUser && $sessionUser['email'] !== $agent->email) {
                return redirect()->route('login')->with('error', 'Anda tidak memiliki akses ke dashboard ini');
            }
            
            return view('agent.dashboard', [
                'user' => $agent,
                'linkReferral' => $linkReferral,
                'portalType' => 'agent',
                'jenisTravelAgent' => $agent->jenis_travel ?? '',
                'stats' => [
                    'totalOrders' => 0, // TODO: implement orders count
                    'totalRevenue' => 0, // TODO: implement revenue
                    'activeBookings' => 0, // TODO: implement bookings count
                ]
            ]);
        }

        // Jika tidak ditemukan, redirect atau tampilkan error
        abort(404, 'Dashboard tidak ditemukan atau tidak aktif');
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

        // Cek agent
        $agent = Agent::where('link_referal', $linkReferral)
            ->where('is_active', true)
            ->first();

        if ($agent) {
            return [
                'user' => $agent,
                'portalType' => 'agent',
                'viewPath' => 'agent'
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
            return [
                'totalOrders' => 0, // TODO: implement
                'totalRevenue' => 0, // TODO: implement
                'activeBookings' => 0, // TODO: implement
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
            abort(404, 'Dashboard tidak ditemukan atau tidak aktif');
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
            abort(404, 'Dashboard tidak ditemukan atau tidak aktif');
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
            abort(404, 'Dashboard tidak ditemukan atau tidak aktif');
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
            abort(404, 'Dashboard tidak ditemukan atau tidak aktif');
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
            abort(404, 'Dashboard tidak ditemukan atau tidak aktif');
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
            abort(404, 'Dashboard tidak ditemukan atau tidak aktif');
        }

        return view($data['viewPath'] . '.order', [
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
            abort(404, 'Dashboard tidak ditemukan atau tidak aktif');
        }

        return view($data['viewPath'] . '.history', [
            'user' => $data['user'],
            'linkReferral' => $linkReferral,
            'portalType' => $data['portalType'],
            'stats' => $this->getStats($data['user'])
        ]);
    }

    /**
     * Halaman Wallet (Agent)
     */
    public function wallet($linkReferral)
    {
        $data = $this->getUserByLinkReferral($linkReferral);
        if (!$data) {
            abort(404, 'Dashboard tidak ditemukan atau tidak aktif');
        }

        return view($data['viewPath'] . '.wallet', [
            'user' => $data['user'],
            'linkReferral' => $linkReferral,
            'portalType' => $data['portalType'],
            'stats' => $this->getStats($data['user'])
        ]);
    }

    /**
     * Halaman Withdraw (Agent)
     */
    public function withdraw($linkReferral)
    {
        $data = $this->getUserByLinkReferral($linkReferral);
        if (!$data) {
            abort(404, 'Dashboard tidak ditemukan atau tidak aktif');
        }

        return view($data['viewPath'] . '.withdraw', [
            'user' => $data['user'],
            'linkReferral' => $linkReferral,
            'portalType' => $data['portalType'],
            'stats' => $this->getStats($data['user'])
        ]);
    }

    /**
     * Halaman Referrals (Agent)
     */
    public function referrals($linkReferral)
    {
        $data = $this->getUserByLinkReferral($linkReferral);
        if (!$data) {
            abort(404, 'Dashboard tidak ditemukan atau tidak aktif');
        }

        return view($data['viewPath'] . '.referrals', [
            'user' => $data['user'],
            'linkReferral' => $linkReferral,
            'portalType' => $data['portalType'],
            'stats' => $this->getStats($data['user'])
        ]);
    }

    /**
     * Halaman Catalog (Agent)
     */
    public function catalog($linkReferral)
    {
        $data = $this->getUserByLinkReferral($linkReferral);
        if (!$data) {
            abort(404, 'Dashboard tidak ditemukan atau tidak aktif');
        }

        return view($data['viewPath'] . '.catalog', [
            'user' => $data['user'],
            'linkReferral' => $linkReferral,
            'portalType' => $data['portalType'],
            'stats' => $this->getStats($data['user'])
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
            'email' => 'required|email|unique:agents,email',
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
}