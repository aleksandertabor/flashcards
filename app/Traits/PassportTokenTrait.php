<?php

namespace App\Traits;

use Illuminate\Http\Request;
use Laravel\Passport\Client;

trait PassportTokenTrait
{
    private $client;

    /**
     * @param $credentials
     * @param $grantType
     * @param $scope
     */
    public function issueToken($credentials, $grantType, $scope = null)
    {
        $this->client = Client::first();

        $credentials = [
            'username' => $credentials['username'] ?? null,
            'password' => $credentials['password'] ?? null,
            'refresh_token' => $credentials['refresh_token'] ?? null,
            'grant_type' => $grantType,
            'client_id' => $this->client->id,
            'client_secret' => $this->client->secret,
            'scope' => $scope,
        ];

        $request = Request::create('api/token', 'POST', $credentials, [], [], [
            'HTTP_Accept' => 'application/json',
        ]);
        $response = app()->handle($request);

        return $response;
    }
}
