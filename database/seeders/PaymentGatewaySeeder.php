<?php

namespace Database\Seeders;

use App\Models\PaymentGateway;
use Illuminate\Database\Seeder;

class PaymentGatewaySeeder extends Seeder
{
    public function run(): void
    {
        PaymentGateway::firstOrCreate(
            ['slug' => 'paystack'],
            [
                'name' => 'Paystack',
                'adapter' => \App\Modules\Payment\Adapters\PaystackAdapter::class,
                'config' => [
                    'public_key' => config('services.paystack.public_key'),
                    'secret_key' => config('services.paystack.secret_key'),
                ],
                'is_active' => true,
            ]
        );
    }
}
