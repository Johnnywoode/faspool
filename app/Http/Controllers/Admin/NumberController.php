<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;

class NumberController extends Controller
{
    public function index()
    {
        $numbers = Order::with(['user', 'service', 'country', 'provider'])
            ->whereNotNull('number')
            ->latest()
            ->paginate(20);

        return view('admin.numbers.index', compact('numbers'));
    }

    public function show($number)
    {
        $order = Order::with(['user', 'service', 'country', 'provider'])
            ->where('number', $number)
            ->orWhere('id', $number)
            ->firstOrFail();

        return view('admin.numbers.show', compact('order'));
    }

    public function release($number)
    {
        $order = Order::where('number', $number)
            ->orWhere('id', $number)
            ->firstOrFail();

        // TODO: release number via provider adapter
        $order->update(['status' => 'cancelled']);

        return back()->with('success', 'Number released successfully.');
    }

    public function extend(Request $request, $number)
    {
        $request->validate([
            'minutes' => 'required|integer|min:5|max:1440',
        ]);

        $order = Order::where('number', $number)
            ->orWhere('id', $number)
            ->firstOrFail();

        if ($order->expires_at) {
            $order->update([
                'expires_at' => $order->expires_at->addMinutes($request->minutes),
            ]);
        }

        return back()->with('success', 'Number expiry extended.');
    }

    public function inventory()
    {
        $inventory = Order::with(['service', 'country', 'provider'])
            ->where('status', 'waiting_sms')
            ->whereNotNull('number')
            ->latest()
            ->paginate(50);

        return view('admin.numbers.inventory', compact('inventory'));
    }
}
