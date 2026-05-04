<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;

class ReportsController extends Controller
{
    /**
     * Display earnings report.
     */
    public function earnings()
    {
        $earnings = Transaction::where('type', 'credit')
            ->where('status', 'completed')
            ->with('wallet.tenant')
            ->paginate(20);
            
        return view('admin.reports.earnings', compact('earnings'));
    }

    /**
     * Display usage report.
     */
    public function usage()
    {
        $orders = Order::with(['user', 'service', 'country', 'tenant'])
            ->paginate(20);
            
        return view('admin.reports.usage', compact('orders'));
    }

    /**
     * Export data to CSV.
     */
    public function generateExport(Request $request)
    {
        $type = $request->input('type', 'orders');
        $filename = "report_{$type}_" . now()->format('YmdHis') . ".csv";
        
        $headers = [
            "Content-type" => "text/csv",
            "Content-Disposition" => "attachment; filename=$filename",
            "Pragma" => "no-cache",
            "Cache-Control" => "must-revalidate, post-check=0, pre-check=0",
            "Expires" => "0"
        ];

        $callback = function() use ($type) {
            $file = fopen('php://output', 'w');
            
            if ($type === 'orders') {
                fputcsv($file, ['ID', 'User', 'Tenant', 'Service', 'Country', 'Cost', 'Price', 'Status', 'Created At']);
                Order::with(['user', 'tenant', 'service', 'country'])->chunk(100, function($orders) use ($file) {
                    foreach ($orders as $order) {
                        fputcsv($file, [
                            $order->uid,
                            $order->user->name ?? 'N/A',
                            $order->tenant->name ?? 'N/A',
                            $order->service->name ?? 'N/A',
                            $order->country->name ?? 'N/A',
                            $order->cost,
                            $order->price,
                            $order->status,
                            $order->created_at
                        ]);
                    }
                });
            } else {
                fputcsv($file, ['ID', 'Tenant', 'Amount', 'Type', 'Status', 'Created At']);
                Transaction::with('wallet.tenant')->chunk(100, function($transactions) use ($file) {
                    foreach ($transactions as $tx) {
                        fputcsv($file, [
                            $tx->id,
                            $tx->wallet->tenant->name ?? 'N/A',
                            $tx->amount,
                            $tx->type,
                            $tx->status,
                            $tx->created_at
                        ]);
                    }
                });
            }
            
            fclose($file);
        };

        return Response::stream($callback, 200, $headers);
    }
}
