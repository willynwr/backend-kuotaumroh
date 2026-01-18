<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Services\UserGoogle; // Import the service
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class AuthController extends Controller
{
    /**
     * Get the Google Auth URL.
     */
    public function redirectToGoogle()
    {
        $google = new UserGoogle();
        return response()->json(['url' => $google->getAuthUrl()]);
    }

    /**
     * Handle the Google Webhook.
     */
    public function handleGoogleCallback(Request $request)
    {
        try {
            $code = $request->input('code');

            if (!$code) {
                return response()->json(['error' => 'Authorization code not provided'], 400);
            }

            $google = new UserGoogle();
            $googleUser = $google->getProfile($code);

            if (!$googleUser) {
                return response()->json(['error' => 'Failed to fetch user profile or access token'], 401);
            }

            // Find or create user
            $user = User::updateOrCreate(
                ['email' => $googleUser->email],
                [
                    'name' => $googleUser->name,
                    'google_id' => $googleUser->id,
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
            return response()->json([
                'error' => 'Authentication failed.',
                'message' => $e->getMessage()
            ], 401);
        }
    }
}
