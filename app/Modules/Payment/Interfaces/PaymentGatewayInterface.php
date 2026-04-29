<?php

namespace App\Modules\Payment\Interfaces;

interface PaymentGatewayInterface
{
    /**
     * Initialize a payment transaction.
     *
     * @param float $amount The amount to charge.
     * @param string $currency The currency code (e.g., 'GHS', 'USD').
     * @param array $userMeta Additional metadata (e.g., user_id, email).
     * @return array An array containing 'success', 'authorization_url', and 'reference'.
     */
    public function initialize(float $amount, string $currency, array $userMeta): array;

    /**
     * Verify a payment transaction.
     *
     * @param string $reference The transaction reference.
     * @return array An array containing 'success', 'amount', 'currency', and 'status'.
     */
    public function verify(string $reference): array;
}
