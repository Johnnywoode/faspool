<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;

class NumberController extends Controller
{
    /**
     * Display a listing of all active numbers (orders).
     */
    public function index()
    {
        $orders = Order::where('status', 'active')
            ->with(['user', 'service', 'country', 'tenant'])
            ->paginate(20);
            
        return view('admin.numbers.index', compact('orders'));
    }

    /**
     * Manually release a number.
     */
    public function release(Order $order)
    {
        $order->update(['status' => 'completed']);
        // Trigger provider release if necessary
        
        return back()->with('success', 'Number released successfully.');
    }

    /**
     * Extend the duration of a rental.
     */
    public function extend(Request $request, Order $order)
    {
        $request->validate(['minutes' => 'required|integer|min:1']);
        
        // Update expiration logic
        $order->expires_at = $order->expires_at->addMinutes($request->minutes);
        $order->save();
        
        return back()->with('success', 'Rental duration extended.');
    }
}
