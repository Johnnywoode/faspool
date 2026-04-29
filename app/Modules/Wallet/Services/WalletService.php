<?php

namespace App\Modules\Wallet\Services;

use App\Models\Wallet;
use App\Models\User;
use App\Models\Transaction;

class WalletService
{
    /**
     * Transfer funds between users (same tenant).
     */
    public function transfer(User $fromUser, User $toUser, float $amount, string $description = ''): array
    {
        // Check same tenant
        if ($fromUser->tenant_id !== $toUser->tenant_id) {
            return [
                'success' => false,
                'message' => 'Users must belong to the same tenant.'
            ];
        }

        // Check balance
        $fromWallet = $fromUser->wallet;
        if (!$fromWallet) {
            return [
                'success' => false,
                'message' => 'Sender wallet not found.'
            ];
        }

        $fromBalance = $fromWallet->balances()->first();
        if (!$fromBalance || $fromBalance->balance < $amount) {
            return [
                'success' => false,
                'message' => 'Insufficient balance.'
            ];
        }

        // Perform transfer
        $toWallet = $toUser->wallet;
        if (!$toWallet) {
            $toWallet = $toUser->wallet()->create();
        }
        $toBalance = $toWallet->balances()->first();
        if (!$toBalance) {
            $toBalance = $toWallet->balances()->create([
                'currency' => 'USD',
                'balance' => 0
            ]);
        }

        $fromBalance->decrement('balance', $amount);
        $toBalance->increment('balance', $amount);

        // Record transactions
        $fromWallet->transactions()->create([
            'amount' => $amount,
            'type' => 'debit',
            'description' => $description ?: "Transfer to {$toUser->name}",
            'status' => 'completed'
        ]);

        $toWallet->transactions()->create([
            'amount' => $amount,
            'type' => 'credit',
            'description' => $description ?: "Transfer from {$fromUser->name}",
            'status' => 'completed'
        ]);

        return [
            'success' => true,
            'message' => 'Transfer completed successfully.'
        ];
    }

    /**
     * Export transactions as CSV.
     */
    public function exportTransactions(User $user): string
    {
        $wallet = $user->wallet;
        if (!$wallet) {
            return '';
        }

        $transactions = $wallet->transactions()->orderBy('created_at', 'desc')->get();
        
        $csv = "Date,Type,Amount,Description,Status\n";
        foreach ($transactions as $transaction) {
            $csv .= sprintf(
                "%s,%s,%.2f,%s,%s\n",
                $transaction->created_at->format('Y-m-d H:i:s'),
                $transaction->type,
                $transaction->amount,
                str_replace(',', ';', $transaction->description),
                $transaction->status
            );
        }

        return $csv;
    }
}
