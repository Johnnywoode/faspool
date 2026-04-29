<?php

namespace App\Modules\Sms\Interfaces;

interface SmsProviderInterface
{
    /**
     * Get available services from the provider.
     */
    public function getServices(): array;

    /**
     * Get available countries from the provider.
     */
    public function getCountries(): array;

    /**
     * Purchase a temporary number.
     *
     * @param string $service
     * @param string $country
     * @return array
     */
    public function purchaseNumber(string $service, string $country): array;

    /**
     * Check for received SMS for a specific order.
     *
     * @param string $orderId
     * @return array
     */
    public function checkSms(string $orderId): array;

    /**
     * Cancel an active number.
     *
     * @param string $orderId
     * @return bool
     */
    public function cancelNumber(string $orderId): bool;
}
