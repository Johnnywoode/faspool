<?php

namespace App\Modules\Sms\Services;

use App\Models\Provider;
use App\Models\Service;
use App\Models\Country;
use App\Models\ProviderService;
use Illuminate\Support\Str;

class SmsSyncService
{
    /**
     * Sync data from a specific provider.
     */
    public function syncProvider(Provider $provider): void
    {
        $adapterClass = $provider->adapter;
        $config = $provider->config;
        $apiKey = $config['api_key'] ?? '';

        if (!class_exists($adapterClass)) {
            return;
        }

        $adapter = new $adapterClass($apiKey);

        $services = $adapter->getServices();
        $countries = $adapter->getCountries();

        foreach ($services as $id => $name) {
            $service = Service::firstOrCreate(
                ['slug' => Str::slug($name)],
                ['name' => $name]
            );

            foreach ($countries as $iso => $countryName) {
                $country = Country::firstOrCreate(
                    ['iso_code' => $iso],
                    ['name' => $countryName]
                );

                // For SMSPool, we might want to fetch prices here, but that's expensive.
                // Usually we fetch prices on demand or in a separate job.
                ProviderService::updateOrCreate(
                    [
                        'provider_id' => $provider->id,
                        'service_id' => $service->id,
                        'country_id' => $country->id,
                    ],
                    [
                        'provider_external_id' => $id,
                        'base_cost' => 0, // Will be updated by a separate price sync job
                        'is_available' => true,
                    ]
                );
            }
        }
    }
}
