<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Agent;
use App\Models\Affiliate;
use App\Models\Freelance;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AdminController extends Controller
{
    public function dashboard()
    {
        $stats = [
            'totalAgents' => Agent::count(),
            'totalAffiliates' => Affiliate::count(),
            'totalOrders' => 0, // TODO: Implement when Order model is ready
            'totalRevenue' => 0, // TODO: Implement when Transaction model is ready
            'pendingWithdrawals' => 0, // TODO: Implement when Withdrawal model is ready
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
        
        return view('admin.packages', compact('packages'));
    }

    public function transactions()
    {
        // TODO: Implement when Transaction model is ready
        $transactions = [];
        
        return view('admin.transactions', compact('transactions'));
    }

    public function withdrawals()
    {
        // TODO: Implement when Withdrawal model is ready
        $withdrawals = [];
        
        return view('admin.withdrawals', compact('withdrawals'));
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
        $stats = [
            'totalRevenue' => 0, // TODO: Calculate from transactions
            'totalOrders' => 0, // TODO: Count from orders
            'activeUsers' => User::where('status', 'active')->count(),
            'conversionRate' => 0, // TODO: Calculate conversion
        ];

        $recentActivity = []; // TODO: Implement activity log
        
        return view('admin.analytics', compact('stats', 'recentActivity'));
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
}
