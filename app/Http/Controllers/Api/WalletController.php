<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Wallet;
use App\Models\Transaction;

class WalletController extends Controller
{
    /**
     * Get wallet balance.
     */
    public function balance()
    {
        $user = auth()->user();
        $wallet = $user->wallet;

        if (!$wallet) {
            $wallet = $user->wallet()->create(['balance' => 0]);
        }

        return response()->json([
            'success' => true,
            'data' => [
                'balance' => (float) $wallet->balance,
                'currency' => 'USD'
            ]
        ]);
    }

    /**
     * Get wallet transactions.
     */
    public function transactions(Request $request)
    {
        $user = auth()->user();
        $wallet = $user->wallet;

        if (!$wallet) {
            return response()->json([
                'success' => true,
                'data' => []
            ]);
        }

        $transactions = $wallet->transactions()
            ->orderBy('created_at', 'desc')
            ->paginate($request->get('per_page', 20));

        return response()->json([
            'success' => true,
            'data' => $transactions
        ]);
    }

    /**
     * Initialize deposit.
     */
    public function deposit(Request $request)
    {
        $request->validate([
            'amount' => 'required|numeric|min:1|max:10000',
            'gateway' => 'required|in:paystack',
        ]);

        $user = auth()->user();
        $paymentManager = new \App\Modules\Payment\PaymentManager();

        try {
            $result = $paymentManager->initializePayment(
                $user,
                $request->amount,
                $request->gateway,
                route('wallet.callback', ['gateway' => $request->gateway])
            );

            return response()->json([
                'success' => true,
                'data' => $result
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }
}
