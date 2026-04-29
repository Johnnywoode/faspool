<?php

namespace Database\Seeders;

use App\Models\Service;
use App\Modules\Sms\Adapters\SmsPoolAdapter;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class ServiceSeeder extends Seeder
{
    public function run(): void
    {
        $adapter = new SmsPoolAdapter('');
        $services = $adapter->getServices();

        if (empty($services)) {
            // Fallback if API fails
            $services = config('data.services', []);
        }

        foreach ($services as $item) {
            $name = is_array($item) ? ($item['name'] ?? null) : (is_object($item) ? ($item->name ?? null) : $item);

            if ($name && is_string($name)) {
                Service::updateOrCreate(
                    ['slug' => Str::slug($name)],
                    ['name' => $name]
                );
            }
        }
    }
}
