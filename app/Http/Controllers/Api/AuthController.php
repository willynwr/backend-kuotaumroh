<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Google\Client;
use Google\Service\Oauth2;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class AuthController extends Controller
{
    protected $client;

    public function __construct()
    {
        $this->client = new Client();
        // Use environment variables instead of file_secret.json for security and convenience
        $this->client->setClientId(env('GOOGLE_CLIENT_ID'));
        $this->client->setClientSecret(env('GOOGLE_CLIENT_SECRET'));
        $this->client->setRedirectUri(env('GOOGLE_REDIRECT'));
        $this->client->addScope('email');
        $this->client->addScope('profile');
        $this->client->setPrompt('select_account'); // Matches user request
    }

    /**
     * Get the Google Auth URL.
     */
    public function redirectToGoogle()
    {
        return response()->json(['url' => $this->client->createAuthUrl()]);
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

            // Exchange code for token
            $token = $this->client->fetchAccessTokenWithAuthCode($code);

            if (isset($token['error'])) {
                return response()->json(['error' => 'Failed to fetch access token'], 401);
            }

            $this->client->setAccessToken($token);

            // Get User Info
            $oauth2 = new Oauth2($this->client);
            $googleUser = $oauth2->userinfo->get();

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
            return response()->json(['error' => 'Authentication failed. ' . $e->getMessage()], 401);
        }
    }
}
