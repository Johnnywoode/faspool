<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Modules\Sms\Controllers\OrderController as SmsOrderController;
use App\Modules\Sms\Services\SmsService;
use Illuminate\Http\Request;
use App\Models\Order;

class OrderController extends Controller
{
    protected $smsService;

    public function __construct(SmsService $smsService)
    {
        $this->smsService = $smsService;
    }

    /**
     * List user's orders.
     */
    public function index()
    {
        $orders = Order::where('user_id', auth()->id())
            ->with('service', 'country', 'provider')
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        return response()->json([
            'success' => true,
            'data' => $orders
        ]);
    }

    /**
     * Purchase a new number via API.
     */
    public function purchase(Request $request)
    {
        $request->validate([
            'service_id' => 'required|exists:services,id',
            'country_id' => 'required|exists:countries,id',
        ]);

        $user = auth()->user();
        $provider = \App\Models\Provider::where('slug', 'sms-pool')->where('status', 'active')->first();

        if (!$provider) {
            return response()->json(['success' => false, 'message' => 'No SMS provider configured.'], 500);
        }

        $result = $this->smsService->purchaseNumber($user, $provider, $request->service_id, $request->country_id);

        if ($result['success']) {
            return response()->json([
                'success' => true,
                'message' => 'Number purchased successfully!',
                'data' => $result['order']
            ], 201);
        }

        return response()->json(['success' => false, 'message' => $result['message']], 400);
    }

    /**
     * Show a specific order.
     */
    public function show($id)
    {
        $order = Order::findOrFail($id);

        if ($order->user_id !== auth()->id()) {
            return response()->json(['success' => false, 'message' => 'Unauthorized'], 403);
        }

        return response()->json([
            'success' => true,
            'data' => $order->load('service', 'country', 'provider')
        ]);
    }

    /**
     * Check SMS status for an order.
     */
    public function checkSms($id)
    {
        $order = Order::findOrFail($id);

        if ($order->user_id !== auth()->id()) {
            return response()->json(['success' => false, 'message' => 'Unauthorized'], 403);
        }

        $result = $this->smsService->checkSms($order);

        return response()->json([
            'success' => true,
            'data' => $result
        ]);
    }
}
