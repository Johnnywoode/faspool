<?php

namespace App\Modules\Payment;

use App\Models\PaymentGateway;
use App\Modules\Payment\Interfaces\PaymentGatewayInterface;
use Exception;

class PaymentManager
{
    /**
     * Get the gateway adapter instance by slug.
     *
     * @param string $gatewaySlug
     * @return PaymentGatewayInterface
     * @throws Exception
     */
    public function driver(string $gatewaySlug): PaymentGatewayInterface
    {
        $gatewayModel = PaymentGateway::where('slug', $gatewaySlug)->first();

        if (!$gatewayModel) {
            throw new Exception("Payment gateway [{$gatewaySlug}] not found.");
        }

        if (!$gatewayModel->is_active) {
            throw new Exception("Payment gateway [{$gatewaySlug}] is not active.");
        }

        $adapterClass = $gatewayModel->adapter;

        if (!class_exists($adapterClass)) {
            throw new Exception("Adapter class [{$adapterClass}] does not exist.");
        }

        $adapter = new $adapterClass($gatewayModel->config ?? []);

        if (!$adapter instanceof PaymentGatewayInterface) {
            throw new Exception("Adapter class [{$adapterClass}] must implement PaymentGatewayInterface.");
        }

        return $adapter;
    }
}
