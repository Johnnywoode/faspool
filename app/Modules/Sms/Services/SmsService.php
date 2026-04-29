<?php

namespace App\Modules\Sms\Services;

use App\Models\Order;
use App\Models\Provider;
use App\Modules\Sms\Interfaces\SmsProviderInterface;

class SmsService
{
    /**
     * Get the adapter for a specific provider.
     */
    protected function getAdapter(Provider $provider): SmsProviderInterface
    {
        $adapterClass = $provider->adapter;
        $apiKey = $provider->config['api_key'] ?? '';
        
        return new $adapterClass($apiKey);
    }

    /**
     * Purchase a new virtual number.
     */
    public function purchaseNumber(\App\Models\User $user, Provider $provider, $serviceId, $countryId): array
    {
        $adapter = $this->getAdapter($provider);

        $service = \App\Models\Service::find($serviceId);
        $country = \App\Models\Country::find($countryId);

        if (!$service || !$country) {
            return ['success' => false, 'message' => 'Invalid service or country'];
        }

        // Use service name and country iso_code for SMSPool API
        $result = $adapter->purchaseNumber($service->name, $country->iso_code);

        if (isset($result['success']) && $result['success'] == 1) {
            $cost = $result['cost'] ?? $result['price'] ?? 0;

            // Calculate final price using PricingEngine
            $pricingEngine = app(\App\Modules\Sms\Services\PricingEngine::class);
            $tenant = $user->tenant;
            $finalPrice = $pricingEngine->getTenantPrice($cost, $tenant);

            // Check wallet balance using WalletBalance relationship
            $wallet = $user->wallet;
            if (!$wallet) {
                $wallet = $user->wallet()->create(['balance' => 0]);
            }

            $walletBalance = $wallet->balance;
            if ($walletBalance < $finalPrice) {
                // Cancel number since we bought it but user can't pay
                $adapter->cancelNumber($result['order_id'] ?? $result['orderid']);
                return ['success' => false, 'message' => 'Insufficient wallet balance.'];
            }

            // Deduct balance
            $wallet->decrement('balance', $finalPrice);
            $wallet->transactions()->create([
                'user_id' => $user->id,
                'amount' => $finalPrice,
                'type' => 'debit',
                'description' => "Purchased number for {$service->name} in {$country->name}",
                'status' => 'completed'
            ]);

            $order = Order::create([
                'user_id' => $user->id,
                'tenant_id' => $user->tenant_id,
                'provider_id' => $provider->id,
                'service_id' => $service->id,
                'country_id' => $country->id,
                'number' => $result['number'] ?? $result['phonenumber'] ?? null,
                'status' => 'waiting_sms',
                'cost' => $cost,
                'price' => $finalPrice,
                'external_id' => $result['order_id'] ?? $result['orderid'] ?? null,
                'expires_at' => now()->addMinutes(15),
            ]);

            return ['success' => true, 'order' => $order];
        }

        return ['success' => false, 'message' => $result['message'] ?? 'Purchase failed due to API error.'];
    }

    /**
     * Check for SMS for an order.
     */
    public function checkSms(Order $order): array
    {
        $adapter = $this->getAdapter($order->provider);
        $result = $adapter->checkSms($order->external_id);

        if (isset($result['sms']) && $result['sms'] != 0) {
            $order->update([
                'status' => 'completed',
                'sms_text' => is_array($result['sms']) ? implode(' ', $result['sms']) : $result['sms'],
            ]);
            
            // Trigger event for real-time notification
            event(new \App\Events\SmsReceived($order));

            return ['status' => 'received', 'sms' => $order->sms_text];
        }

        if ($order->expires_at && now()->greaterThan($order->expires_at)) {
            $order->update(['status' => 'expired']);
            return ['status' => 'expired'];
        }

        return ['status' => 'waiting'];
    }
}
