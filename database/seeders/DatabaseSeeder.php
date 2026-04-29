<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // 1. Create Roles first
        $this->call(RolesAndPermissionsSeeder::class);

        // 2. Create Default Tenants
        $this->call(TenantSeeder::class);

        // 3. Create Admin User for the default tenant
        $tenant = \App\Models\Tenant::where('is_default', true)->first();
        
        if ($tenant) {
            // Create admin user
            $admin = User::firstOrCreate(
                ['email' => 'admin@faspool.com'],
                [
                    'tenant_id' => $tenant->id,
                    'name' => 'Admin User',
                    'password' => 'password',
                ]
            ); 

            // Assign Admin Role
            $admin->assignRole('admin');

            // Create a test user
            $testUser = User::firstOrCreate(
                ['email' => 'user@faspool.com'],
                [
                    'tenant_id' => $tenant->id,
                    'name' => 'Test User',
                    'password' => 'password',
                ]
            );
            $testUser->assignRole('user');

            // Create sub-account user
            $subUser = User::firstOrCreate(
                ['email' => 'subuser@faspool.com'],
                [
                    'tenant_id' => $tenant->id,
                    'name' => 'Sub Account User',
                    'password' => 'password',
                ]
            );
            $subUser->assignRole('sub_user');

            // Create wallet for test user (uses wallet_balances table)
            if (!$testUser->wallet) {
                $wallet = $testUser->wallet()->create([
                    'tenant_id' => $tenant->id,
                ]);
                $wallet->balances()->create([
                    'currency' => 'GHS',
                    'balance' => 100.00,
                ]);
            }
        }

        // 4. Run other data seeders (if they exist)
        $this->callServiceSeeders();
    }

    protected function callServiceSeeders(): void
    {
        $this->call(ServiceSeeder::class);
        $this->call(CountrySeeder::class);
        $this->call(PaymentGatewaySeeder::class);
        $this->call(ProviderSeeder::class);
        $this->call(WalletSeeder::class);
    }
}
