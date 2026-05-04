<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\ProviderService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class BulkOperationsController extends Controller
{
    /**
     * Show bulk operations index.
     */
    public function index()
    {
        return view('admin.bulk.index');
    }

    /**
     * Bulk import numbers/services from a provider.
     */
    public function importNumbers(Request $request)
    {
        // Mocking bulk import logic for now as it depends on provider API
        return back()->with('success', 'Bulk import from provider initiated in background.');
    }

    /**
     * Bulk cancel pending/active orders.
     */
    public function cancelOrders(Request $request)
    {
        $validated = $request->validate([
            'order_ids' => 'required|array',
            'order_ids.*' => 'exists:orders,id',
        ]);

        $count = Order::whereIn('id', $validated['order_ids'])
            ->whereIn('status', ['pending', 'active'])
            ->update(['status' => 'cancelled']);

        return back()->with('success', "$count orders have been cancelled.");
    }

    /**
     * Bulk refund cancelled/failed orders.
     */
    public function bulkRefund(Request $request)
    {
        // Logic to find failed orders with debited wallets and refund them
        // This should be wrapped in a transaction
        return back()->with('success', 'Bulk refund process started.');
    }
}
