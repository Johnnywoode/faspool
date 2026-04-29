<?php

namespace App\Modules\Payment\Adapters;

use App\Modules\Payment\Interfaces\PaymentGatewayInterface;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;

class PaystackAdapter implements PaymentGatewayInterface
{
    protected string $secretKey;
    protected string $baseUrl = 'https://api.paystack.co';

    public function __construct(array $config)
    {
        $this->secretKey = $config['secret_key'] ?? '';
    }

    /**
     * {@inheritdoc}
     */
    public function initialize(float $amount, string $currency, array $userMeta): array
    {
        $reference = (string) Str::uuid();
        
        // Paystack expects amount in sub-units (kobo/pesewas)
        $amountInSubUnits = $amount * 100;

        $response = Http::withToken($this->secretKey)
            ->post("{$this->baseUrl}/transaction/initialize", [
                'email' => $userMeta['email'],
                'amount' => $amountInSubUnits,
                'reference' => $reference,
                'callback_url' => route('wallet.callback', ['gateway' => 'paystack']),
                'metadata' => [
                    'user_id' => $userMeta['user_id'],
                    'tenant_id' => $userMeta['tenant_id'] ?? null,
                ],
            ]);

        if ($response->successful()) {
            $data = $response->json();
            return [
                'success' => true,
                'authorization_url' => $data['data']['authorization_url'],
                'reference' => $reference,
            ];
        }

        return [
            'success' => false,
            'message' => $response->json()['message'] ?? 'Paystack initialization failed',
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function verify(string $reference): array
    {
        $response = Http::withToken($this->secretKey)
            ->get("{$this->baseUrl}/transaction/verify/{$reference}");

        if ($response->successful()) {
            $data = $response->json();
            $status = $data['data']['status'];

            if ($status === 'success') {
                return [
                    'success' => true,
                    'amount' => $data['data']['amount'] / 100, // Convert back from sub-units
                    'currency' => $data['data']['currency'],
                    'status' => 'completed',
                ];
            }

            return [
                'success' => false,
                'message' => 'Payment was not successful. Status: ' . $status,
                'status' => 'failed',
            ];
        }

        return [
            'success' => false,
            'message' => 'Payment verification failed',
            'status' => 'failed',
        ];
    }
}
