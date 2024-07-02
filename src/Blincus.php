<?php
namespace Blincus;

use GuzzleHttp\Client;

class Blincus
{
    private $authUrl = 'http://localhost:89/api/token';
    private $transactionUrl = 'http://localhost:89/api/sandbox/v1/payment';
    private $client;
    private $email;
    private $password;

    public function __construct($email, $password)
    {
        $this->client = new Client();
        $this->email = $email;
        $this->password = $password;
    }

    public function getAccessToken()
    {
        $response = $this->client->post($this->authUrl, [
            'json' => [
                'email' => $this->email,
                'password' => $this->password,
            ]
        ]);

        if ($response->getStatusCode() == 200) {
            $data = json_decode($response->getBody(), true);
            return $data['access_token']['token'] ?? null;
        }

        return null;
    }

    public function makeTransaction($accessToken, $transactionData)
    {
        $response = $this->client->post($this->transactionUrl, [
            'headers' => [
                'Authorization' => 'Bearer ' . $accessToken,
                'Content-Type' => 'application/json',
                'Accept' => 'application/json',
            ],
            'json' => $transactionData,
        ]);

        if ($response->getStatusCode() == 200) {
            return json_decode($response->getBody(), true);
        }

        return null;
    }
}
