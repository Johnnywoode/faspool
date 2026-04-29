<?php

namespace App\Modules\Sms\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Country;
use App\Models\Provider;
use App\Models\Service;
use App\Modules\Sms\Services\SmsService;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    protected $smsService;

    public function __construct(SmsService $smsService)
    {
        $this->smsService = $smsService;
    }

    /**
     * Show the order page.
     */
    public function index()
    {
        $services = Service::where('status', 'active')->orderBy('name')->take(20)->get();
        $countries = Country::where('status', 'active')->orderBy('name')->take(20)->get();
        
        return view('orders.index', compact('services', 'countries'));
    }

    /**
     * Search services and countries via AJAX.
     */
    public function search(Request $request)
    {
        $serviceQuery = $request->get('service_q', '');
        $countryQuery = $request->get('country_q', '');

        $services = Service::where('status', 'active');
        if (!empty($serviceQuery)) {
            $services->where('name', 'like', "%{$serviceQuery}%");
        }
        $services = $services->orderBy('name')->take(50)->get();

        $countries = Country::where('status', 'active');
        if (!empty($countryQuery)) {
            $countries->where('name', 'like', "%{$countryQuery}%");
        }
        $countries = $countries->orderBy('name')->take(50)->get();

        return response()->json([
            'services' => $services,
            'countries' => $countries
        ]);
    }

    /**
     * Handle the purchase request.
     */
    public function purchase(Request $request)
    {
        $request->validate([
            'service_id' => 'required|exists:services,id',
            'country_id' => 'required|exists:countries,id',
        ]);

        $user = auth()->user();
        
        // Find default provider (SmsPool)
        $provider = Provider::where('slug', 'sms-pool')->where('status', 'active')->first();

        if (!$provider) {
            return back()->with('error', 'No SMS provider configured.');
        }

        // Check wallet balance before attempting purchase
        $service = \App\Models\Service::find($request->service_id);
        $country = \App\Models\Country::find($request->country_id);
        
        $pricingEngine = app(\App\Modules\Sms\Services\PricingEngine::class);
        $estimatedPrice = $pricingEngine->getTenantPrice(1.00, $user->tenant); // Rough estimate
        
        $wallet = $user->wallet;
        if (!$wallet || $wallet->balance < $estimatedPrice) {
            return back()->with('error', 'Insufficient wallet balance. Please deposit funds first.');
        }

        $result = $this->smsService->purchaseNumber($user, $provider, $request->service_id, $request->country_id);

        if ($result['success']) {
            return redirect()->route('orders.show', $result['order']->id)
                             ->with('success', 'Number purchased successfully!');
        }

        return back()->with('error', $result['message']);
    }

    /**
     * Show a specific order (waiting for SMS).
     */
    public function show($id)
    {
        $order = \App\Models\Order::findOrFail($id);
        
        // Ensure user owns the order
        if ($order->user_id !== auth()->id()) {
            abort(403);
        }

        return view('orders.show', compact('order'));
    }

    /**
     * API endpoint to check SMS status.
     */
    public function checkSms($id)
    {
        $order = \App\Models\Order::findOrFail($id);
        
        if ($order->user_id !== auth()->id()) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $result = $this->smsService->checkSms($order);

        return response()->json($result);
    }
}
