<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Log;

class SettingsController extends Controller
{
    public function index()
    {
        return view('admin.settings.index');
    }

    public function updateGeneral(Request $request)
    {
        $request->validate([
            'app_name'     => 'nullable|string|max:255',
            'app_url'      => 'nullable|url|max:255',
            'default_currency' => 'nullable|string|size:3',
        ]);

        // TODO: persist to config/settings table or .env
        return back()->with('success', 'General settings updated.');
    }

    public function updatePayment(Request $request)
    {
        $request->validate([
            'paystack_public_key'  => 'nullable|string',
            'paystack_secret_key'  => 'nullable|string',
        ]);

        // TODO: persist payment gateway credentials
        return back()->with('success', 'Payment settings updated.');
    }

    public function updateNotification(Request $request)
    {
        // TODO: persist notification settings
        return back()->with('success', 'Notification settings updated.');
    }

    public function updateSecurity(Request $request)
    {
        // TODO: persist security settings
        return back()->with('success', 'Security settings updated.');
    }

    public function updateEmail(Request $request)
    {
        $request->validate([
            'mail_host'       => 'nullable|string|max:255',
            'mail_port'       => 'nullable|integer',
            'mail_username'   => 'nullable|string|max:255',
            'mail_password'   => 'nullable|string',
            'mail_from_address' => 'nullable|email|max:255',
            'mail_from_name'  => 'nullable|string|max:255',
        ]);

        // TODO: persist email settings
        return back()->with('success', 'Email settings updated.');
    }

    public function clearCache()
    {
        Artisan::call('cache:clear');
        Artisan::call('config:clear');
        Artisan::call('route:clear');
        Artisan::call('view:clear');

        return back()->with('success', 'Application cache cleared successfully.');
    }

    public function createBackup()
    {
        // TODO: implement database backup (e.g. via spatie/laravel-backup)
        return back()->with('success', 'Backup created successfully.');
    }

    public function viewLogs()
    {
        $logPath = storage_path('logs/laravel.log');
        $logs    = '';

        if (file_exists($logPath)) {
            $logs = file_get_contents($logPath);
            // Only show last 200 lines to avoid memory issues
            $lines = array_slice(explode("\n", $logs), -200);
            $logs  = implode("\n", $lines);
        }

        return view('admin.settings.logs', compact('logs'));
    }

    public function systemInfo()
    {
        $info = [
            'php_version'   => phpversion(),
            'laravel_version' => app()->version(),
            'server_software' => $_SERVER['SERVER_SOFTWARE'] ?? 'N/A',
            'db_driver'     => config('database.default'),
            'cache_driver'  => config('cache.default'),
            'queue_driver'  => config('queue.default'),
            'environment'   => app()->environment(),
            'debug_mode'    => config('app.debug') ? 'Enabled' : 'Disabled',
        ];

        return view('admin.settings.system-info', compact('info'));
    }
}
