<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SystemSetting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;

class SettingsController extends Controller
{
    /**
     * Show the settings index page.
     */
    public function index()
    {
        $settings = SystemSetting::all()->groupBy('group');
        return view('admin.settings.index', compact('settings'));
    }

    /**
     * Update general system settings.
     */
    public function updateGeneral(Request $request)
    {
        $validated = $request->validate([
            'site_name' => 'nullable|string|max:255',
            'site_description' => 'nullable|string',
            'contact_email' => 'nullable|email',
            'impersonation_enabled' => 'nullable|boolean',
        ]);

        foreach ($validated as $key => $value) {
            $type = is_bool($value) ? 'boolean' : 'string';
            SystemSetting::set($key, $value, 'general', $type);
        }

        return back()->with('success', 'General settings updated.');
    }

    /**
     * Update payment gateway settings.
     */
    public function updatePayment(Request $request)
    {
        $validated = $request->validate([
            'payment_markup' => 'nullable|numeric|min:0',
            'min_deposit' => 'nullable|numeric|min:1',
            'max_deposit' => 'nullable|numeric|min:1',
        ]);

        foreach ($validated as $key => $value) {
            SystemSetting::set($key, $value, 'payment', 'float');
        }

        return back()->with('success', 'Payment settings updated.');
    }

    /**
     * Update notification settings.
     */
    public function updateNotification(Request $request)
    {
        $validated = $request->validate([
            'notify_on_new_user' => 'nullable|boolean',
            'notify_on_large_deposit' => 'nullable|boolean',
            'large_deposit_threshold' => 'nullable|numeric|min:0',
        ]);

        foreach ($validated as $key => $value) {
            $type = is_bool($value) ? 'boolean' : 'float';
            SystemSetting::set($key, $value, 'notification', $type);
        }

        return back()->with('success', 'Notification settings updated.');
    }

    /**
     * Clear various application caches.
     */
    public function clearCache()
    {
        Artisan::call('cache:clear');
        Artisan::call('config:clear');
        Artisan::call('route:clear');
        Artisan::call('view:clear');

        return back()->with('success', 'Application cache cleared successfully.');
    }

    /**
     * Get system-wide information.
     */
    public function systemInfo()
    {
        $info = [
            'php_version' => phpversion(),
            'laravel_version' => app()->version(),
            'server_info' => $_SERVER['SERVER_SOFTWARE'] ?? 'N/A',
            'database_connection' => config('database.default'),
            'environment' => app()->environment(),
        ];

        return view('admin.settings.system-info', compact('info'));
    }
}
