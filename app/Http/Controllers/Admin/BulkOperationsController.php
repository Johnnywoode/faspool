<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class BulkOperationsController extends Controller
{
    public function index()
    {
        return view('admin.bulk.index');
    }

    public function importNumbers(Request $request)
    {
        $request->validate([
            'file' => 'required|file|mimes:csv,txt|max:10240',
        ]);

        // TODO: parse CSV and import numbers
        return back()->with('success', 'Numbers import queued successfully.');
    }

    public function exportOrders(Request $request)
    {
        $request->validate([
            'date_from' => 'nullable|date',
            'date_to'   => 'nullable|date|after_or_equal:date_from',
            'status'    => 'nullable|in:waiting_sms,completed,expired,cancelled',
        ]);

        // TODO: queue export job and return download link
        return back()->with('success', 'Orders export queued. Download will be available shortly.');
    }

    public function cancelOrders(Request $request)
    {
        $request->validate([
            'order_ids' => 'required|array',
            'order_ids.*' => 'required|string|exists:orders,id',
        ]);

        // TODO: cancel selected orders in bulk
        return back()->with('success', count($request->order_ids) . ' orders cancelled.');
    }

    public function refund(Request $request)
    {
        $request->validate([
            'order_ids'   => 'required|array',
            'order_ids.*' => 'required|string|exists:orders,id',
        ]);

        // TODO: process bulk refunds
        return back()->with('success', 'Bulk refund processed.');
    }

    public function status($batchId)
    {
        // TODO: check bulk job status via Laravel Bus batch
        return response()->json([
            'batch_id' => $batchId,
            'status'   => 'pending',
            'progress' => 0,
        ]);
    }

    public function downloadTemplate()
    {
        $headers = [
            'Content-Type'        => 'text/csv',
            'Content-Disposition' => 'attachment; filename="numbers-import-template.csv"',
        ];

        $callback = function () {
            $file = fopen('php://output', 'w');
            fputcsv($file, ['service', 'country', 'quantity']);
            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    public function validate(Request $request)
    {
        $request->validate([
            'file' => 'required|file|mimes:csv,txt|max:10240',
        ]);

        // TODO: validate CSV contents and return preview
        return response()->json([
            'valid' => true,
            'rows'  => 0,
            'errors' => [],
        ]);
    }
}
