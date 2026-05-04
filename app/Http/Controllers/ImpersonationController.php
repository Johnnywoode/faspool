<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\SystemSetting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ImpersonationController extends Controller
{
    /**
     * Start impersonating a user.
     */
    public function start(User $user)
    {
        // Check if feature is enabled
        if (!SystemSetting::get('impersonation_enabled', true)) {
            return back()->with('error', 'Impersonation is currently disabled.');
        }

        // Check permissions
        if (!auth()->user()->hasRole('admin') && !auth()->user()->can('impersonate users')) {
            abort(403);
        }

        // Don't impersonate self
        if ($user->id === auth()->id()) {
            return back()->with('error', 'You cannot impersonate yourself.');
        }

        // Store original admin ID in session
        session()->put('impersonated_by', auth()->id());

        // Login as the user
        Auth::login($user);

        return redirect()->route('dashboard')->with('success', 'You are now impersonating ' . $user->name);
    }

    /**
     * Stop impersonating and return to admin.
     */
    public function stop()
    {
        if (!session()->has('impersonated_by')) {
            return redirect()->route('dashboard');
        }

        $adminId = session()->pull('impersonated_by');
        $admin = User::find($adminId);

        if ($admin) {
            Auth::login($admin);
            return redirect()->route('admin.users.index')->with('success', 'Returned to admin session.');
        }

        Auth::logout();
        return redirect()->route('login');
    }
}
