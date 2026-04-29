<?php

namespace Database\Seeders;

use App\Models\Tenant;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TenantSeeder extends Seeder
{
    public function run(): void
    {
        // Clear existing data
        DB::statement('SET FOREIGN_KEY_CHECKS=0');
        Tenant::truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1');

        // Auto-detect domain based on environment
        $domain = $this->getCurrentDomain();
        
        $this->command->info("Detected domain: {$domain}");

        $tenants = [
            [
                'id' => \Illuminate\Support\Str::uuid(),
                'name' => 'Faspool HQ',
                'domain' => $domain,
                'api_key' => \Illuminate\Support\Str::random(32),
                'settings' => json_encode([
                    'pricing' => [
                        'markup' => 0.50,
                        'demand_adjustment' => 0,
                        'risk_factor' => 0
                    ],
                    'theme' => 'dark'
                ]),
                'status' => 'active',
                'is_default' => true,
            ],
            // [
            //     'id' => \Illuminate\Support\Str::uuid(),
            //     'name' => 'Demo Tenant',
            //     'domain' => 'demo.' . $domain . '.test',
            //     'api_key' => \Illuminate\Support\Str::random(32), 
            //     'settings' => json_encode([
            //         'pricing' => [
            //             'markup' => 0.75,
            //             'demand_adjustment' => 0,
            //             'risk_factor' => 0
            //         ],
            //         'theme' => 'dark'
            //     ]),
            //     'status' => 'active',
            //     'is_default' => false,
            // ]
        ];

        foreach ($tenants as $tenant) {
            Tenant::create($tenant);
        }
        $this->command->info('Tenants seeded successfully!');
    }

    private function getCurrentDomain(): string
    {
        // Check if running in console
        if (app()->runningInConsole()) {
            // Get from environment variable or config
            $domain = env('APP_DOMAIN');
            
            if ($domain) {
                return $domain;
            }
            
            // Try to get from server environment
            if (isset($_SERVER['HTTP_HOST'])) {
                return explode(':', $_SERVER['HTTP_HOST'])[0];
            }
            
            // Default for local development
            return '127.0.0.1';
        }

        return env('APP_DOMAIN') ?? explode(':', request()->getHost())[0];
        
        // Running via web request
        // return explode(':', request()->getHost())[0];
    }
}
