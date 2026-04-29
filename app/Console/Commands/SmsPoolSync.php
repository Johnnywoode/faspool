<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Provider;
use App\Modules\Sms\Services\SmsSyncService;

class SmsPoolSync extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'smspool:sync';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Sync services and countries from SMSPool';

    /**
     * Execute the console command.
     */
    public function handle(SmsSyncService $syncService)
    {
        $this->info('Starting SMSPool sync...');

        $provider = Provider::where('adapter', \App\Modules\Sms\Adapters\SmsPoolAdapter::class)->first();

        if (!$provider) {
            $this->error('SMSPool provider not found in database. Please seed or create it first.');
            return 1;
        }

        try {
            $syncService->syncProvider($provider);
            $this->info('Sync completed successfully!');
        } catch (\Exception $e) {
            $this->error('Sync failed: ' . $e->getMessage());
            return 1;
        }

        return 0;
    }
}
