<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Transaction;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AnalyticsController extends Controller
{
    public function index()
    {
        return $this->dashboard();
    }

    public function dashboard()
    {
        $stats = [
            'total_orders'    => Order::count(),
            'total_users'     => User::count(),
            'total_revenue'   => Transaction::where('type', 'credit')
                                    ->where('status', 'completed')
                                    ->sum('amount'),
            'pending_orders'  => Order::where('status', 'waiting_sms')->count(),
        ];

        return view('admin.analytics.index', compact('stats'));
    }

    public function revenue()
    {
        $revenueByDay = Transaction::where('type', 'credit')
            ->where('status', 'completed')
            ->selectRaw('DATE(created_at) as date, SUM(amount) as total')
            ->groupBy('date')
            ->orderBy('date', 'desc')
            ->take(30)
            ->get();

        return view('admin.analytics.revenue', compact('revenueByDay'));
    }

    public function usage()
    {
        $usageByDay = Order::selectRaw('DATE(created_at) as date, COUNT(*) as total')
            ->groupBy('date')
            ->orderBy('date', 'desc')
            ->take(30)
            ->get();

        return view('admin.analytics.usage', compact('usageByDay'));
    }

    public function numbers()
    {
        return view('admin.analytics.numbers');
    }

    public function orders()
    {
        $orders = Order::with(['user', 'service', 'country', 'provider'])
            ->latest()
            ->paginate(20);

        return view('admin.analytics.orders', compact('orders'));
    }

    public function realTime()
    {
        return view('admin.analytics.real-time');
    }
}
