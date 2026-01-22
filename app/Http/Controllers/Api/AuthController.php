<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Agent;
use App\Models\Affiliate;
use App\Models\Freelance;
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

            // Check if user exists in any table
            $user = null;
            $role = null;

            // 1. Check agents table
            $agent = Agent::where('email', $email)->first();
            if ($agent) {
                $user = $agent;
                $jenis = strtolower($agent->jenis_agent ?? 'agent');
                
                if (str_contains($jenis, 'freelance')) {
                    $role = 'freelance';
                } elseif (str_contains($jenis, 'affiliate')) {
                    $role = 'affiliate';
                } else {
                    $role = 'agent';
                }
            }

            // 2. Check affiliates table if not found in agents
            if (!$user) {
                $affiliate = Affiliate::where('email', $email)->first();
                if ($affiliate) {
                    $user = $affiliate;
                    $role = 'affiliate';
                }
            }

            // 3. Check freelances table if still not found
            if (!$user) {
                $freelance = Freelance::where('email', $email)->first();
                if ($freelance) {
                    $user = $freelance;
                    $role = 'freelance';
                }
            }

            // User not found
            if (!$user) {
                return response()->json([
                    'success' => false,
                    'is_registered' => false,
                    'user' => [
                        'email' => $email,
                        'name' => $name,
                    ],
                    'message' => 'User belum terdaftar'
                ], 404);
            }

            // User found - generate token
            $token = $user->createToken('auth-token')->plainTextToken;

            return response()->json([
                'success' => true,
                'is_registered' => true,
                'user' => [
                    'id' => $user->id,
                    'nama_pic' => $user->nama_pic ?? $user->nama ?? $name,
                    'nama' => $user->nama_pic ?? $user->nama ?? $name,
                    'email' => $user->email,
                    'jenis_agent' => $user->jenis_agent ?? null,
                    'agent_code' => $user->agent_code ?? null,
                    'link_referral' => $user->link_referral ?? $user->link_referal ?? null,
                    'status' => $user->status ?? 'approved',
                ],
                'token' => $token,
                'role' => $role
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
