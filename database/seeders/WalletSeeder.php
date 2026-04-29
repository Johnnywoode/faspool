<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Wallet;
use App\Models\WalletBalance;
use Illuminate\Database\Seeder;

class WalletSeeder extends Seeder
{
    public function run(): void
    {
        $users = User::all();

        foreach ($users as $user) {
            $wallet = Wallet::firstOrCreate(
                ['user_id' => $user->id, 'tenant_id' => $user->tenant_id]
            );

            WalletBalance::firstOrCreate(
                ['wallet_id' => $wallet->id, 'currency' => 'GHS'],
                ['balance' => 100.00] // Give everyone $100 for testing
            );
        }
    }
}
