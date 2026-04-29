<?php

namespace App\Modules\Order\Services;

use App\Models\Order;
use App\Models\User;

class OrderService
{
    /**
     * Cancel an order and refund if eligible.
     */
    public function cancelOrder(Order $order): array
    {
        // Check if order can be cancelled
        if (!in_array($order->status, ['waiting_sms', 'pending'])) {
            return [
                'success' => false,
                'message' => 'Order cannot be cancelled in current status.'
            ];
        }

        // Refund to wallet
        $wallet = $order->user->wallet;
        if ($wallet) {
            $walletBalance = $wallet->balances()->first();
            if ($walletBalance) {
                $walletBalance->increment('balance', $order->price);
                
                $wallet->transactions()->create([
                    'amount' => $order->price,
                    'type' => 'credit',
                    'description' => "Refund for cancelled order #{$order->id}",
                    'status' => 'completed'
                ]);
            }
        }

        $order->update(['status' => 'cancelled']);

        return [
            'success' => true,
            'message' => 'Order cancelled and refunded successfully.'
        ];
    }

    /**
     * Get order statistics for a user.
     */
    public function getUserStats(User $user): array
    {
        $orders = Order::where('user_id', $user->id);
        
        return [
            'total' => $orders->count(),
            'completed' => (clone $orders)->where('status', 'completed')->count(),
            'cancelled' => (clone $orders)->where('status', 'cancelled')->count(),
            'waiting' => (clone $orders)->where('status', 'waiting_sms')->count(),
            'total_spent' => (clone $orders)->sum('price'),
        ];
    }
}
