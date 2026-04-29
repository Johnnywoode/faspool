<?php

namespace App\Modules\Sms\Adapters;

use App\Modules\Sms\Interfaces\SmsProviderInterface;
use Illuminate\Support\Facades\Http;

class SmsPoolAdapter implements SmsProviderInterface
{
    protected string $apiKey;
    protected string $baseUrl;

    public function __construct(string $apiKey)
    {
        $this->apiKey = $apiKey;
        $this->baseUrl =  config('sms.providers.sms-pool.api_url');
    }

    /**
     * {@inheritdoc}
     */
    public function getServices(): array
    {
        $response = Http::post($this->baseUrl . 'service/retrieve_all');
        return $response->json() ?: [];
    }

    /**
     * {@inheritdoc}
     */
    public function getCountries(): array
    {
        $response = Http::post($this->baseUrl . 'country/retrieve_all');
        return $response->json() ?: [];
    }

    /**
     * {@inheritdoc}
     */
    public function purchaseNumber(string $service, string $country): array
    {
        $response = Http::get($this->baseUrl . 'purchase/number', [
            'key' => $this->apiKey,
            'service' => $service,
            'country' => $country,
        ]);
        
        return $response->json() ?: ['success' => 0, 'message' => 'API connection failed'];
    }

    /**
     * {@inheritdoc}
     */
    public function checkSms(string $orderId): array
    {
        $response = Http::get($this->baseUrl . 'sms/check', [
            'key' => $this->apiKey,
            'orderid' => $orderId,
        ]);
        
        return $response->json() ?: ['sms' => 0];
    }

    /**
     * {@inheritdoc}
     */
    public function cancelNumber(string $orderId): bool
    {
        $response = Http::get($this->baseUrl . 'purchase/cancel', [
            'key' => $this->apiKey,
            'orderid' => $orderId,
        ]);
        
        $data = $response->json();
        return isset($data['success']) && $data['success'] == 1;
    }
}
