<?php
namespace RemnWeb;

use GuzzleHttp\Client;

class Billing
{
    private Client $client;
    private string $shopId;
    private string $secret;
    private string $remnaKey;
    private string $remnaUrl;

    public function __construct()
    {
        $dotenv = \Dotenv\Dotenv::createImmutable(__DIR__ . '/../');
        $dotenv->load();
        $this->client = new Client();
        $this->shopId = $_ENV['YOOKASSA_SHOP_ID'] ?? '';
        $this->secret = $_ENV['YOOKASSA_SECRET'] ?? '';
        $this->remnaKey = $_ENV['REMNAWAVE_API_KEY'] ?? '';
        $this->remnaUrl = $_ENV['REMNAWAVE_API_URL'] ?? 'https://api.remnawave.example/v1/';
    }

    public function createPayment(float $amount, string $returnUrl): array
    {
        $response = $this->client->post('https://api.yookassa.ru/v3/payments', [
            'auth' => [$this->shopId, $this->secret],
            'json' => [
                'amount' => ['value' => number_format($amount, 2, '.', ''), 'currency' => 'RUB'],
                'confirmation' => [
                    'type' => 'redirect',
                    'return_url' => $returnUrl
                ],
                'capture' => true,
                'description' => 'VPN Subscription'
            ]
        ]);
        return json_decode($response->getBody()->getContents(), true);
    }

    public function getPlan(string $name): ?array
    {
        $db = DB::get();
        $stmt = $db->prepare('SELECT * FROM plans WHERE name = :n');
        $stmt->execute([':n' => $name]);
        return $stmt->fetch(\PDO::FETCH_ASSOC) ?: null;
    }

    public function activateSubscription(int $userId, string $plan): bool
    {
        $client = new Client([
            'base_uri' => $this->remnaUrl,
            'headers' => ['Authorization' => 'Bearer ' . $this->remnaKey]
        ]);
        // This is a placeholder call. Replace with real Remnawave API request.
        $response = $client->post('activate', [
            'json' => ['user_id' => $userId, 'plan' => $plan]
        ]);
        return $response->getStatusCode() === 200;
    }

    public function subscribeUser(int $userId, int $planId): bool
    {
        $db = DB::get();
        $stmt = $db->prepare('INSERT INTO subscriptions (user_id, plan_id, status, start_date) VALUES (:u, :p, :s, NOW())');
        return $stmt->execute([':u' => $userId, ':p' => $planId, ':s' => 'active']);
    }
}
