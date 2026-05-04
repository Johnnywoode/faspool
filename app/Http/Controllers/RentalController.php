<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Service;
use App\Models\Country;
use App\Models\Provider;
use App\Modules\Sms\Services\SmsService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RentalController extends Controller
{
    protected SmsService $smsService;

    public function __construct(SmsService $smsService)
    {
        $this->smsService = $smsService;
    }

    /**
     * Display a listing of long-term rentals.
     */
    public function index()
    {
        $rentals = Order::where('user_id', Auth::id())
            ->where('is_rental', true)
            ->latest()
            ->paginate(10);
            
        $services = Service::where('is_active', true)->where('category', 'rental')->get();
        if ($services->isEmpty()) {
            $services = Service::where('is_active', true)->take(10)->get(); // Fallback
        }
        
        $countries = Country::where('is_active', true)->get();

        return view('rentals.index', compact('rentals', 'services', 'countries'));
    }

    /**
     * Store a newly created rental.
     */
    public function store(Request $request)
    {
        $request->validate([
            'service_id' => 'required|exists:services,id',
            'country_id' => 'required|exists:countries,id',
            'duration' => 'required|integer|min:1|max:30', // Days
        ]);

        $user = Auth::user();
        $provider = Provider::where('is_active', true)->first();

        if (!$provider) {
            return back()->with('error', 'No SMS providers available.');
        }

        // Logic to calculate rental price based on duration
        // For now, using a simple multiplier
        $basePrice = 5.00; // Mock base rental price
        $totalPrice = $basePrice * $request->duration;

        $wallet = $user->wallet;
        $balance = $wallet ? $wallet->balances()->first()->balance : 0;

        if ($balance < $totalPrice) {
            return back()->with('error', 'Insufficient wallet balance for this rental.');
        }

        // Mocking rental purchase logic
        // In reality, this would call the provider API for long-term numbers
        $order = Order::create([
            'uid' => \Illuminate\Support\Str::uuid(),
            'user_id' => $user->id,
            'tenant_id' => $user->tenant_id,
            'provider_id' => $provider->id,
            'service_id' => $request->service_id,
            'country_id' => $request->country_id,
            'number' => 'RENTAL-' . rand(1000, 9999),
            'status' => 'active',
            'is_rental' => true,
            'cost' => $totalPrice * 0.7,
            'price' => $totalPrice,
            'expires_at' => now()->addDays($request->duration),
        ]);

        $wallet->balances()->first()->decrement('balance', $totalPrice);
        $wallet->transactions()->create([
            'amount' => $totalPrice,
            'type' => 'debit',
            'description' => 'Long-term rental for ' . $request->duration . ' days',
            'status' => 'completed',
        ]);

        return back()->with('success', 'Long-term rental initiated successfully.');
    }

    /**
     * Extend an existing rental.
     */
    public function extend(Request $request, Order $rental)
    {
        $this->authorize('view', $rental);
        
        $request->validate([
            'days' => 'required|integer|min:1',
        ]);

        $user = Auth::user();
        $pricePerDay = 5.00;
        $totalPrice = $pricePerDay * $request->days;

        $balance = $user->wallet->balances()->first()->balance;

        if ($balance < $totalPrice) {
            return back()->with('error', 'Insufficient balance to extend rental.');
        }

        $rental->update([
            'expires_at' => $rental->expires_at->addDays($request->days),
        ]);

        $user->wallet->balances()->first()->decrement('balance', $totalPrice);
        
        return back()->with('success', 'Rental extended successfully.');
    }
}
