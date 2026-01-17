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
        $this->client->setClientId(env('GOOGLE_CLIENT_ID'));
        $this->client->setClientSecret(env('GOOGLE_CLIENT_SECRET'));
        $this->client->setRedirectUri(env('GOOGLE_REDIRECT'));
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
                return null;
            }

            $this->client->setAccessToken($token);

            $oauth2 = new Oauth2($this->client);
            return $oauth2->userinfo->get();
        } catch (\Exception $e) {
            return null;
        }
    }
}
