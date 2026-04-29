<?php

namespace App\Jobs;

use App\Models\Order;
use App\Modules\Sms\Services\SmsService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class SmsPollingJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected Order $order;

    /**
     * Create a new job instance.
     */
    public function __construct(Order $order)
    {
        $this->order = $order;
    }

    /**
     * Execute the job.
     */
    public function handle(SmsService $smsService): void
    {
        // Only poll if order is in waiting state
        if ($this->order->status !== 'waiting_sms') {
            return;
        }

        $result = $smsService->checkSms($this->order);

        if ($result['status'] === 'waiting') {
            // Requeue the job after 10 seconds
            self::dispatch($this->order)->delay(now()->addSeconds(10));
        }
    }
}
