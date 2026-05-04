<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Transaction;
use App\Models\User;
use App\Models\Tenant;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AnalyticsController extends Controller
{
    /**
     * Dashboard analytics summary.
     */
    public function index()
    {
        $stats = [
            'total_revenue' => Transaction::where('type', 'credit')->where('status', 'completed')->sum('amount'),
            'total_orders' => Order::count(),
            'total_users' => User::count(),
            'total_tenants' => Tenant::count(),
        ];

        $revenueData = Transaction::where('type', 'credit')
            ->where('status', 'completed')
            ->select(DB::raw('DATE(created_at) as date'), DB::raw('SUM(amount) as total'))
            ->groupBy('date')
            ->orderBy('date', 'desc')
            ->limit(30)
            ->get();

        return view('admin.analytics.index', compact('stats', 'revenueData'));
    }

    /**
     * Revenue specific analytics.
     */
    public function revenue()
    {
        $revenueByTenant = Transaction::where('type', 'credit')
            ->where('status', 'completed')
            ->select('tenants.name', DB::raw('SUM(transactions.amount) as total'))
            ->join('wallets', 'transactions.wallet_id', '=', 'wallets.id')
            ->join('tenants', 'wallets.tenant_id', '=', 'tenants.id')
            ->groupBy('tenants.name')
            ->get();

        return view('admin.analytics.revenue', compact('revenueByTenant'));
    }

    /**
     * Usage and order volume analytics.
     */
    public function usage()
    {
        $orderVolume = Order::select(DB::raw('DATE(created_at) as date'), DB::raw('COUNT(*) as count'))
            ->groupBy('date')
            ->orderBy('date', 'desc')
            ->limit(30)
            ->get();

        return view('admin.analytics.usage', compact('orderVolume'));
    }
}
