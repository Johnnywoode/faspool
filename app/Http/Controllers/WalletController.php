<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use App\Models\WalletBalance;
use App\Modules\Payment\PaymentManager;
use Illuminate\Http\Request;

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
        $transactions = auth()->user()->wallet->transactions()->latest()->take(10)->get() ?? collect();
        $gateways = \App\Models\PaymentGateway::where('is_active', true)->get();
        return view('wallet.deposit', compact('transactions', 'gateways'));
    }

    /**
     * Initialize payment.
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
                'user_id' => $user->id,
                'email' => $user->email,
                'tenant_id' => $user->tenant_id,
            ];

            $result = $adapter->initialize((float) $request->amount, 'GHS', $userMeta);

            if ($result['success']) {
                // Create a pending transaction
                Transaction::create([
                    'id' => $result['reference'],
                    'wallet_id' => $user->wallet->id,
                    'type' => 'credit',
                    'amount' => $request->amount,
                    'currency' => 'GHS',
                    'status' => 'pending',
                    'meta' => ['gateway' => $gateway],
                ]);

                return redirect($result['authorization_url']);
            }

            return back()->with('status', 'error')->with('message', 'Payment initialization failed: ' . ($result['message'] ?? 'Unknown error'));

        } catch (\Exception $e) {
            return back()->with('status', 'error')->with('message', $e->getMessage());
        }
    }

    /**
     * Handle payment callback.
     */
    public function paymentCallback(Request $request, string $gateway)
    {
        $reference = $request->reference; // Most gateways pass reference via query

        if (!$reference) {
            return redirect()->route('wallet.deposit')->with('status', 'error')->with('message', 'No payment reference supplied');
        }

        try {
            $adapter = $this->paymentManager->driver($gateway);
            $result = $adapter->verify($reference);

            if ($result['success']) {
                $transaction = Transaction::where('id', $reference)->first();

                if ($transaction && $transaction->status === 'pending') {
                    // Prevent double counting if the amount differs (highly unusual but safe)
                    // For now, trust the requested amount or use the verified amount
                    $transaction->update(['status' => 'completed']);

                    // Update wallet balance
                    $walletBalance = WalletBalance::firstOrCreate(
                        ['wallet_id' => $transaction->wallet_id, 'currency' => $result['currency']],
                        ['balance' => 0]
                    );
                    $walletBalance->increment('balance', $transaction->amount);

                    return redirect()->route('wallet.deposit')->with('status', 'success')->with('message', 'Wallet topped up successfully!');
                }
                return redirect()->route('wallet.deposit')->with('status', 'info')->with('message', 'Payment was already processed.');
            }

            // Mark failed if transaction exists
            Transaction::where('id', $reference)->update(['status' => 'failed']);
            return redirect()->route('wallet.deposit')->with('status', 'error')->with('message', $result['message']);

        } catch (\Exception $e) {
            return redirect()->route('wallet.deposit')->with('status', 'error')->with('message', $e->getMessage());
        }
    }
}

