<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Agent;
use App\Models\Affiliate;
use App\Models\Freelance;
use App\Models\Admin;
use Illuminate\Http\Request;
use Laravel\Socialite\Facades\Socialite;

class AuthController extends Controller
{
    /**
     * Get the Google Auth URL.
     */
    public function getGoogleAuthUrl()
    {
        try {
            $url = Socialite::driver('google')
                ->stateless()
                ->redirect()
                ->getTargetUrl();
            
            return response()->json(['url' => $url]);
        } catch (\Exception $e) {
            \Log::error('Google Auth URL Error: ' . $e->getMessage());
            return response()->json([
                'error' => 'Failed to generate Google auth URL',
                'message' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Handle the Google Callback.
     */
    public function handleGoogleCallback(Request $request)
    {
        try {
            // Get Google user
            $googleUser = Socialite::driver('google')->stateless()->user();

            if (!$googleUser) {
                return response()->json([
                    'success' => false,
                    'message' => 'Failed to fetch user from Google'
                ], 401);
            }

            $email = $googleUser->getEmail();
            $name = $googleUser->getName();

            // Check if user exists in any table - collect ALL matching accounts
            $accounts = [];

            // Check all tables
            $admin = Admin::where('email', $email)->first();
            $agent = Agent::where('email', $email)->first();
            $affiliate = Affiliate::where('email', $email)->first();
            $freelance = Freelance::where('email', $email)->first();

            // Collect all found accounts
            if ($admin) {
                $token = $admin->createToken('auth-token-admin')->plainTextToken;
                $accounts[] = [
                    'id' => $admin->id,
                    'email' => $admin->email,
                    'nama' => $admin->nama ?? $name,
                    'no_wa' => $admin->no_wa ?? null,
                    'role' => 'admin',
                    'token' => $token
                ];
            }
            
            if ($agent) {
                $token = $agent->createToken('auth-token-agent')->plainTextToken;
                $jenis = strtolower($agent->jenis_agent ?? 'agent');
                $role = 'agent';
                
                if (str_contains($jenis, 'freelance')) {
                    $role = 'freelance';
                } elseif (str_contains($jenis, 'affiliate')) {
                    $role = 'affiliate';
                }
                
                $accounts[] = [
                    'id' => $agent->id,
                    'email' => $agent->email,
                    'nama_pic' => $agent->nama_pic ?? $agent->nama ?? $name,
                    'nama' => $agent->nama_pic ?? $agent->nama ?? $name,
                    'jenis_agent' => $agent->jenis_agent ?? null,
                    'agent_code' => $agent->agent_code ?? null,
                    'link_referral' => $agent->link_referral ?? $agent->link_referal ?? null,
                    'status' => $agent->status ?? 'approved',
                    'role' => $role,
                    'source_table' => 'agent',
                    'token' => $token
                ];
            }
            
            if ($affiliate) {
                $token = $affiliate->createToken('auth-token-affiliate')->plainTextToken;
                $accounts[] = [
                    'id' => $affiliate->id,
                    'email' => $affiliate->email,
                    'nama_pic' => $affiliate->nama_pic ?? $affiliate->nama ?? $name,
                    'nama' => $affiliate->nama_pic ?? $affiliate->nama ?? $name,
                    'jenis_agent' => 'affiliate',
                    'agent_code' => $affiliate->agent_code ?? null,
                    'link_referral' => $affiliate->link_referral ?? $affiliate->link_referal ?? null,
                    'status' => $affiliate->status ?? 'approved',
                    'role' => 'affiliate',
                    'source_table' => 'affiliate',
                    'token' => $token
                ];
            }
            
            if ($freelance) {
                $token = $freelance->createToken('auth-token-freelance')->plainTextToken;
                $accounts[] = [
                    'id' => $freelance->id,
                    'email' => $freelance->email,
                    'nama_pic' => $freelance->nama_pic ?? $freelance->nama ?? $name,
                    'nama' => $freelance->nama_pic ?? $freelance->nama ?? $name,
                    'jenis_agent' => 'freelance',
                    'agent_code' => $freelance->agent_code ?? null,
                    'link_referral' => $freelance->link_referral ?? $freelance->link_referal ?? null,
                    'status' => $freelance->status ?? 'approved',
                    'role' => 'freelance',
                    'source_table' => 'freelance',
                    'token' => $token
                ];
            }

            // User not found in any table
            if (empty($accounts)) {
                return response()->json([
                    'success' => false,
                    'is_registered' => false,
                    'user' => [
                        'email' => $email,
                        'name' => $name,
                    ],
                    'accounts' => [],
                    'message' => 'User belum terdaftar'
                ], 404);
            }

            // Return all found accounts - let frontend decide which to use based on intent
            // For backward compatibility, also include first matching user/role
            $primaryAccount = $accounts[0];
            $primaryRole = $primaryAccount['role'];
            $primaryToken = $primaryAccount['token'];
            
            // Build userData without token for backward compatibility
            $userData = $primaryAccount;
            unset($userData['token']);

            return response()->json([
                'success' => true,
                'is_registered' => true,
                'user' => $userData,
                'token' => $primaryToken,
                'role' => $primaryRole,
                'accounts' => $accounts, // NEW: all accounts for this email
                'has_multiple_accounts' => count($accounts) > 1
            ]);

        } catch (\Exception $e) {
            \Log::error('Google Callback Error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Gagal autentikasi dengan Google',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Save user session
     */
    public function saveSession(Request $request)
    {
        try {
            $request->validate([
                'id' => 'required',
                'email' => 'required|email',
                'name' => 'required',
                'role' => 'required|in:agent,affiliate,freelance,admin',
            ]);

            // Save to session
            session([
                'user' => [
                    'id' => $request->id,
                    'email' => $request->email,
                    'name' => $request->name,
                    'role' => $request->role,
                    'agentCode' => $request->agentCode,
                    'link_referral' => $request->link_referral,
                ]
            ]);

            // Force save session immediately
            session()->save();

            // Log for debugging
            \Log::info('Session saved for user: ' . $request->email, [
                'session_id' => session()->getId(),
                'user_data' => session('user')
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Session saved',
                'session_id' => session()->getId()
            ]);
        } catch (\Exception $e) {
            \Log::error('Save Session Error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to save session',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Destroy user session (logout)
     */
    public function destroySession(Request $request)
    {
        session()->forget('user');
        session()->flush();
        
        return response()->json([
            'success' => true,
            'message' => 'Session destroyed'
        ]);
    }
}
