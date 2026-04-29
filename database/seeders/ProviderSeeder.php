<?php

namespace Database\Seeders;

use App\Models\Provider;
use Illuminate\Database\Seeder;

class ProviderSeeder extends Seeder
{
    public function run(): void
    {
        Provider::firstOrCreate(
            ['slug' => 'sms-pool'],
            [
                'name' => 'SMSPool',
                'adapter' => \App\Modules\Sms\Adapters\SmsPoolAdapter::class,
                'config' => ['api_key' => config('sms.providers.sms-pool.api_key')],
                'is_active' => true
            ]
        );
    }
}
