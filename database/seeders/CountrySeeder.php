<?php

namespace Database\Seeders;

use App\Models\Country;
use App\Modules\Sms\Adapters\SmsPoolAdapter;
use Illuminate\Database\Seeder;

class CountrySeeder extends Seeder
{
    public function run(): void
    {
        $adapter = new SmsPoolAdapter('');
        $countries = $adapter->getCountries();

        if (empty($countries)) {
            // Fallback if API fails
            $countries = config('data.countries');
        }

        foreach ($countries as $country) {
            Country::updateOrCreate(
                ['iso_code' => strtoupper($country['short_name'])],
                [
                    'name' => $country['name'],
                    'region' => $country['region'],
                    'status' => 'active'
                ]
            );
        }
    }
}
