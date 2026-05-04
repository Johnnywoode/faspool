<?php

namespace App\Http\Controllers;

use App\Models\Country;
use App\Models\Order;
use App\Models\Provider;
use App\Models\Service;
use App\Modules\Sms\Services\SmsService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class OrderController extends Controller
{
    protected SmsService $smsService;

    public function __construct(SmsService $smsService)
    {
        $this->smsService = $smsService;
    }

    /**
     * Display the quick order page.
     */
    public function index()
    {
        $services = Service::where('is_active', true)->take(20)->get();
        $countries = Country::where('is_active', true)->take(20)->get();
        
        return view('orders.index', compact('services', 'countries'));
    }

    /**
     * Handle search requests for services and countries.
     */
    public function search(Request $request)
    {
        $serviceQ = $request->get('service_q');
        $countryQ = $request->get('country_q');

        $services = Service::where('is_active', true)
            ->when($serviceQ, function($query) use ($serviceQ) {
                return $query->where('name', 'like', "%{$serviceQ}%");
            })
            ->take(20)
            ->get();

        $countries = Country::where('is_active', true)
            ->when($countryQ, function($query) use ($countryQ) {
                return $query->where('name', 'like', "%{$countryQ}%");
            })
            ->take(20)
            ->get();

        return response()->json([
            'services' => $services,
            'countries' => $countries
        ]);
    }

    /**
     * Purchase a virtual number.
     */
    public function purchase(Request $request)
    {
        $request->validate([
            'service_id' => 'required|exists:services,id',
            'country_id' => 'required|exists:countries,id',
        ]);

        $user = Auth::user();
        
        // Find best provider (simplification: pick first active)
        $provider = Provider::where('is_active', true)->first();

        if (!$provider) {
            return back()->with('error', 'No SMS providers are currently available.');
        }

        $result = $this->smsService->purchaseNumber($user, $provider, $request->service_id, $request->country_id);

        if ($result['success']) {
            return redirect()->route('orders.show', $result['order']->uid)
                ->with('success', 'Number purchased successfully!');
        }

        return back()->with('error', $result['message'] ?? 'Purchase failed.');
    }

    /**
     * Display order details and wait for SMS.
     */
    public function show(Order $order)
    {
        $this->authorize('view', $order);
        
        return view('orders.show', compact('order'));
    }

    /**
     * AJAX endpoint to check for SMS.
     */
    public function checkSms(Order $order)
    {
        $this->authorize('view', $order);

        if ($order->status === 'completed') {
            return response()->json(['status' => 'received', 'sms' => $order->sms_text]);
        }

        if ($order->status === 'expired' || $order->status === 'cancelled') {
            return response()->json(['status' => $order->status]);
        }

        $result = $this->smsService->checkSms($order);
        
        return response()->json($result);
    }
}
