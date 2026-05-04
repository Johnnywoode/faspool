<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ReportsController extends Controller
{
    public function earnings()
    {
        $earnings = Transaction::where('type', 'credit')
            ->where('status', 'completed')
            ->latest()
            ->paginate(20);

        return view('admin.reports.earnings', compact('earnings'));
    }

    public function usage()
    {
        $usage = Order::with(['user', 'service', 'country'])
            ->latest()
            ->paginate(20);

        return view('admin.reports.usage', compact('usage'));
    }

    public function numberPerformance()
    {
        return view('admin.reports.number-performance');
    }

    public function orderAnalytics()
    {
        $analytics = [
            'total'     => Order::count(),
            'completed' => Order::where('status', 'completed')->count(),
            'expired'   => Order::where('status', 'expired')->count(),
            'waiting'   => Order::where('status', 'waiting_sms')->count(),
        ];

        return view('admin.reports.order-analytics', compact('analytics'));
    }

    public function export()
    {
        return view('admin.reports.export');
    }

    public function generateExport(Request $request)
    {
        $request->validate([
            'type'       => 'required|in:orders,transactions,users',
            'date_from'  => 'nullable|date',
            'date_to'    => 'nullable|date|after_or_equal:date_from',
        ]);

        // TODO: generate and store CSV export file
        return back()->with('success', 'Export is being generated. You will be notified when ready.');
    }

    public function schedule()
    {
        return view('admin.reports.schedule');
    }

    public function storeSchedule(Request $request)
    {
        $request->validate([
            'report_type' => 'required|string',
            'frequency'   => 'required|in:daily,weekly,monthly',
            'email'       => 'required|email',
        ]);

        // TODO: store scheduled report configuration
        return back()->with('success', 'Report schedule saved.');
    }

    public function deleteSchedule($schedule)
    {
        // TODO: delete scheduled report
        return back()->with('success', 'Report schedule deleted.');
    }

    public function download($file)
    {
        $path = storage_path('app/reports/' . $file);

        if (!file_exists($path)) {
            abort(404, 'Report file not found.');
        }

        return response()->download($path);
    }
}
