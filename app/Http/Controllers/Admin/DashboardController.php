<?php

namespace App\Http\Controllers\Admin;

use App\Models\Order;
use App\Models\Transaction;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;

class DashboardController extends Controller
{
    public function index()
    {
        // Get platform-wide order statistics
        $totalOrders     = Order::count();
        $completedOrders = Order::where('status', 'completed')->count();
        $waitingOrders   = Order::where('status', 'waiting_sms')->count();

        // Calculate total revenue (all completed credit transactions)
        $totalRevenue = Transaction::where('type', 'credit')
            ->where('status', 'completed')
            ->sum('amount');

        // Calculate total spent across all orders
        $totalSpent = Order::sum('price');

        // Total registered users
        $totalUsers = User::count();

        // Get last 7 days for chart
        $chartData = $this->getChartData();

        return view('admin.dashboard', compact(
            'totalOrders',
            'completedOrders',
            'waitingOrders',
            'totalRevenue',
            'totalSpent',
            'totalUsers',
            'chartData'
        ));
    }

    protected function getChartData(): array
    {
        $labels       = [];
        $revenueData  = [];
        $ordersData   = [];

        for ($i = 6; $i >= 0; $i--) {
            $date     = now()->subDays($i)->format('Y-m-d');
            $labels[] = now()->subDays($i)->format('M d');

            $revenueData[] = round(
                Transaction::where('type', 'credit')
                    ->where('status', 'completed')
                    ->whereDate('created_at', $date)
                    ->sum('amount'),
                2
            );

            $ordersData[] = Order::whereDate('created_at', $date)->count();
        }

        return [
            'labels'  => $labels,
            'revenue' => $revenueData,
            'orders'  => $ordersData,
        ];
    }
}
