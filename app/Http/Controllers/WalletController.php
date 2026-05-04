<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use App\Models\WalletBalance;
use App\Modules\Payment\PaymentManager;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class WalletController extends Controller
{
    protected PaymentManager $paymentManager;

    public function __construct(PaymentManager $paymentManager)
    {
        $this->paymentManager = $paymentManager;
    }

    /**
     * Show the deposit page.
     */
    public function deposit()
    {
        $user         = auth()->user();
        $wallet       = $user->wallet;
        $transactions = $wallet
            ? $wallet->transactions()->latest()->take(10)->get()
            : collect();

        $gateways = \App\Models\PaymentGateway::where('is_active', true)->get();

        return view('wallet.deposit', compact('transactions', 'gateways'));
    }

    /**
     * Initialize payment with the chosen gateway.
     */
    public function initializePayment(Request $request, string $gateway)
    {
        $request->validate([
            'amount' => 'required|numeric|min:1',
        ]);

        $user = auth()->user();

        try {
            $adapter = $this->paymentManager->driver($gateway);

            $userMeta = [
                'user_id'   => $user->id,
                'email'     => $user->email,
                'tenant_id' => $user->tenant_id,
            ];

            $result = $adapter->initialize((float) $request->amount, 'GHS', $userMeta);

            if ($result['success']) {
                // Ensure the user has a wallet
                $wallet = $user->wallet ?? $user->wallet()->create([
                    'tenant_id' => $user->tenant_id,
                ]);

                // Create a pending transaction, storing the gateway reference in the 'reference' column
                Transaction::create([
                    'wallet_id'   => $wallet->id,
                    'type'        => 'credit',
                    'amount'      => $request->amount,
                    'currency'    => 'GHS',
                    'status'      => 'pending',
                    'reference'   => $result['reference'],
                    'description' => 'Wallet top-up via ' . ucfirst($gateway),
                    'meta'        => ['gateway' => $gateway],
                ]);

                return redirect($result['authorization_url']);
            }

            return back()
                ->with('status', 'error')
                ->with('message', 'Payment initialization failed: ' . ($result['message'] ?? 'Unknown error'));

        } catch (\Exception $e) {
            return back()
                ->with('status', 'error')
                ->with('message', $e->getMessage());
        }
    }

    /**
     * Handle payment callback from the gateway.
     */
    public function paymentCallback(Request $request, string $gateway)
    {
        // Most gateways pass reference as a query parameter
        $reference = $request->reference ?? $request->trxref ?? null;

        if (!$reference) {
            return redirect()->route('wallet.deposit')
                ->with('status', 'error')
                ->with('message', 'No payment reference supplied.');
        }

        try {
            $adapter = $this->paymentManager->driver($gateway);
            $result  = $adapter->verify($reference);

            if ($result['success']) {
                // Look up transaction by the 'reference' column (not the UUID primary key)
                $transaction = Transaction::where('reference', $reference)->first();

                if ($transaction && $transaction->status === 'pending') {
                    DB::transaction(function () use ($transaction, $result) {
                        $transaction->update(['status' => 'completed']);

                        // Update or create the wallet balance record
                        $walletBalance = WalletBalance::firstOrCreate(
                            [
                                'wallet_id' => $transaction->wallet_id,
                                'currency'  => $result['currency'] ?? 'GHS',
                            ],
                            ['balance' => 0]
                        );

                        $walletBalance->increment('balance', $transaction->amount);
                    });

                    return redirect()->route('wallet.deposit')
                        ->with('status', 'success')
                        ->with('message', 'Wallet topped up successfully!');
                }

                return redirect()->route('wallet.deposit')
                    ->with('status', 'info')
                    ->with('message', 'Payment was already processed.');
            }

            // Mark transaction as failed if it exists
            Transaction::where('reference', $reference)->update(['status' => 'failed']);

            return redirect()->route('wallet.deposit')
                ->with('status', 'error')
                ->with('message', $result['message'] ?? 'Payment verification failed.');

        } catch (\Exception $e) {
            return redirect()->route('wallet.deposit')
                ->with('status', 'error')
                ->with('message', $e->getMessage());
        }
    }
}
