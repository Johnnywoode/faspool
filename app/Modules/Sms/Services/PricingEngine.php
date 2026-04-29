<?php

namespace App\Modules\Sms\Services;

use App\Models\ProviderService;

class PricingEngine
{
    /**
     * Calculate the final price for a service.
     * 
     * Formula: final_price = base_cost + markup + demand_adjustment + risk_factor
     */
    public function calculatePrice(float $baseCost, array $settings): float
    {
        $markup = (float) ($settings['markup'] ?? 0.50); // Default $0.50 markup
        $demandAdjustment = (float) ($settings['demand_adjustment'] ?? 0);
        $riskFactor = (float) ($settings['risk_factor'] ?? 0);

        return round($baseCost + $markup + $demandAdjustment + $riskFactor, 2);
    }

    /**
     * Get the final price for a specific provider service and tenant.
     */
    public function getTenantPrice(float $baseCost, \App\Models\Tenant $tenant): float
    {
        $settings = $tenant->settings['pricing'] ?? [];
        return $this->calculatePrice($baseCost, $settings);
    }

    /**
     * Get the final price for a specific service and country for a tenant.
     */
    public function getPriceForService(int $serviceId, int $countryId, \App\Models\Tenant $tenant): ?float
    {
        $providerService = \App\Models\ProviderService::where('service_id', $serviceId)
            ->whereHas('provider', function ($query) {
                $query->where('status', 'active');
            })
            ->first();

        if (!$providerService) {
            return null;
        }

        $baseCost = $providerService->cost;
        $settings = $tenant->settings['pricing'] ?? [];
        
        // Allow per-service markup override
        $serviceKey = "service_{$serviceId}";
        if (isset($tenant->settings['pricing'][$serviceKey])) {
            $settings = array_merge($settings, $tenant->settings['pricing'][$serviceKey]);
        }

        return $this->calculatePrice($baseCost, $settings);
    }
}
