<?php

namespace App\Http\Controllers\Admin;

use App\Models\Order;
use App\Models\Transaction;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        // Get wallet balance
        $walletBalance = $user->wallet ? $user->wallet->balance : 0;

        // Get order statistics
        $totalOrders = Order::count();
        $completedOrders = Order::where('status', 'completed')->count();
        $waitingOrders = Order::where('status', 'waiting_sms')->count();

        // Calculate total deposited (via wallet relationship)
        $wallet = $user->wallet;
        $totalDeposited = 0;
        if ($wallet) {
            $totalDeposited = $wallet->transactions()
                ->where('type', 'credit')
                ->where('description', 'like', '%deposit%')
                ->sum('amount');
        }

        // Calculate total spent
        $totalSpent = Order::where('user_id', $user->id)->sum('price');

        // Get last 7 days for chart
        $chartData = $this->getChartData($user);
        

        return view('dashboard.index', compact(
            'walletBalance',
            'totalOrders',
            'completedOrders',
            'waitingOrders',
            'totalDeposited',
            'totalSpent',
            'chartData'
        ));
    }

    protected function getChartData($user)
    {
        $labels = [];
        $depositedData = [];
        $spentData = [];

        $wallet = $user->wallet;

        // Get data for last 7 days
        for ($i = 6; $i >= 0; $i--) {
            $date = now()->subDays($i)->format('Y-m-d');
            $labels[] = now()->subDays($i)->format('M d');

            // Deposits for this day (via wallet)
            $deposited = 0;
            if ($wallet) {
                $deposited = $wallet->transactions()
                    ->where('type', 'credit')
                    ->where('description', 'like', '%deposit%')
                    ->whereDate('created_at', $date)
                    ->sum('amount');
            }
            $depositedData[] = round($deposited, 2);

            // Spent (orders) for this day
            $spent = Order::where('user_id', $user->id)
                ->whereDate('created_at', $date)
                ->sum('price');
            $spentData[] = round($spent, 2);
        }

        return [
            'labels' => $labels,
            'deposited' => $depositedData,
            'spent' => $spentData
        ];
    }
}
