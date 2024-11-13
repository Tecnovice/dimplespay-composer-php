<?php

namespace DimplesPay;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;

class DimplesPayClient
{
    private $client;
    private $baseUrl;
    private $clientId;
    private $secretId;
    private $accessToken;

    public function __construct(string $baseUrl, string $clientId, string $secretId)
    {
        $this->client = new Client();
        $this->baseUrl = $baseUrl;
        $this->clientId = $clientId;
        $this->secretId = $secretId;
    }

    public function authenticate(): void
    {
        $endpoint = "{$this->baseUrl}/authentication/token";

        try {
            $response = $this->client->post($endpoint, [
                'json' => [
                    'client_id' => $this->clientId,
                    'secret_id' => $this->secretId,
                ],
                'headers' => [
                    'accept' => 'application/json',
                    'content-type' => 'application/json',
                ],
            ]);

            $data = json_decode($response->getBody(), true);

            if ($data['type'] === 'success') {
                $this->accessToken = $data['data']['access_token'];
            } else {
                throw new AuthenticationException('Authentication failed.');
            }
        } catch (RequestException $e) {
            throw new AuthenticationException($e->getMessage());
        }
    }

    public function createPayment(float $amount, string $currency, string $returnUrl, string $cancelUrl = '', string $custom = ''): array
    {
        $this->ensureAuthenticated();

        $endpoint = "{$this->baseUrl}/payment/create";

        try {
            $response = $this->client->post($endpoint, [
                'json' => [
                    'amount' => round($amount, 2),
                    'currency' => strtoupper($currency),
                    'return_url' => $returnUrl,
                    'cancel_url' => $cancelUrl,
                    'custom' => $custom,
                ],
                'headers' => [
                    'Authorization' => "Bearer {$this->accessToken}",
                    'accept' => 'application/json',
                    'content-type' => 'application/json',
                ],
            ]);

            $data = json_decode($response->getBody(), true);

            if ($data['type'] === 'success') {
                return $data['data'];
            } else {
                throw new PaymentException('Failed to create payment.');
            }
        } catch (RequestException $e) {
            throw new PaymentException($e->getMessage());
        }
    }

    public function checkPaymentStatus(string $token): array
    {
        $this->ensureAuthenticated();

        $endpoint = "{$this->baseUrl}/payment/status/{$token}";

        try {
            $response = $this->client->get($endpoint, [
                'headers' => [
                    'Authorization' => "Bearer {$this->accessToken}",
                    'accept' => 'application/json',
                ],
            ]);

            return json_decode($response->getBody(), true);
        } catch (RequestException $e) {
            throw new PaymentException($e->getMessage());
        }
    }

    private function ensureAuthenticated(): void
    {
        if (!$this->accessToken) {
            throw new AuthenticationException('Client is not authenticated. Call authenticate() first.');
        }
    }
}
