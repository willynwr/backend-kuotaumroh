<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Laravel\Socialite\Facades\Socialite;

class AuthController extends Controller
{
    /**
     * Get the Google Auth URL.
     */
    public function redirectToGoogle()
    {
        // Use stateless() to generate the URL
        $url = Socialite::driver('google')->stateless()->redirect()->getTargetUrl();
        return response()->json(['url' => $url]);
    }

    /**
     * Handle the Google Webhook.
     */
    public function handleGoogleCallback(Request $request)
    {
        try {
            // Retrieve user from Google using stateless()
            // This handles the 'code' retrieval automatically from the request
            $googleUser = Socialite::driver('google')->stateless()->user();

            if (!$googleUser) {
                return response()->json(['error' => 'Failed to fetch user profile or access token'], 401);
            }

            // Find or create user
            $user = User::updateOrCreate(
                ['email' => $googleUser->getEmail()],
                [
                    'name' => $googleUser->getName(),
                    'google_id' => $googleUser->getId(),
                    'password' => bcrypt(Str::random(16)) // Random password
                ]
            );

            // Create Sanctum Token
            $authToken = $user->createToken('auth_token')->plainTextToken;

            return response()->json([
                'access_token' => $authToken,
                'token_type' => 'Bearer',
                'user' => $user
            ]);

        } catch (\Exception $e) {
            \Log::error('Google Login Error: ' . $e->getMessage());
            return response()->json([
                'error' => 'Authentication failed.',
                'message' => $e->getMessage()
            ], 401); // 401 Unauthorized is better than 500 for auth failures, unless it's a code error
        }
    }
}
