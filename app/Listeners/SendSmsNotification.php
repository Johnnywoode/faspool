<?php

namespace App\Listeners;

use App\Events\SmsReceived;
use Illuminate\Support\Facades\Log;

class SendSmsNotification
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(SmsReceived $event): void
    {
        $order = $event->order;
        $user = $order->user;

        // Log the SMS received
        Log::info("SMS received for order {$order->id}: {$order->sms_text}");

        // You can implement real-time notifications here
        // For example, using WebSockets, Pusher, or broadcasting

        // For now, we'll just log it
        // In production, you might want to:
        // - Send an email notification
        // - Send a browser push notification
        // - Update a dashboard in real-time
    }
}
