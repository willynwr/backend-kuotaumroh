<?php
namespace App\Services;

use Google\Client;
use Google\Service\Oauth2;

class UserGoogle
{
    protected $client;

    public function __construct()
    {
        $this->client = new Client();
        $this->client->setClientId(config('services.google.client_id'));
        $this->client->setClientSecret(config('services.google.client_secret'));
        $this->client->setRedirectUri(config('services.google.redirect'));
        $this->client->addScope('email');
        $this->client->addScope('profile');
        $this->client->setPrompt('select_account');
    }

    public function getAuthUrl()
    {
        return $this->client->createAuthUrl();
    }

    public function getProfile($code)
    {
        try {
            $token = $this->client->fetchAccessTokenWithAuthCode($code);

            if (isset($token['error'])) {
                \Log::error('Google OAuth Token Error: ' . json_encode($token));
                return null;
            }

            $this->client->setAccessToken($token);

            $oauth2 = new Oauth2($this->client);
            return $oauth2->userinfo->get();
        } catch (\Throwable $e) {
            \Log::error('Google OAuth Exception: ' . $e->getMessage());
            return null;
        }
    }
}
