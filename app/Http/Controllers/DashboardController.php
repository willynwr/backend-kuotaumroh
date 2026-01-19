<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Affiliate;
use App\Models\Freelance;
use App\Models\Agent;

class DashboardController extends Controller
{
    /**
     * Handle dashboard akses berdasarkan link_referral
     * Route: dash/{link_referral}
     */
    public function show($linkReferral)
    {
        // Cek apakah link_referral adalah milik affiliate
        $affiliate = Affiliate::where('link_referral', $linkReferral)
            ->where('is_active', true)
            ->with('agents')
            ->first();

        if ($affiliate) {
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
            return view('agent.dashboard', [
                'user' => $agent,
                'linkReferral' => $linkReferral,
                'portalType' => 'agent',
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
}
