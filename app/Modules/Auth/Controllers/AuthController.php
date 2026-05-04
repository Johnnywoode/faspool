<?php

namespace App\Modules\Auth\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegisterRequest;
use App\Models\User;
use App\Models\Tenant;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function showLogin()
    {
        return view('auth.login');
    }

    public function login(LoginRequest $request)
    {
        $credentials = $request->validated();
        // Attempt to authenticate
        if (Auth::attempt($credentials)) {
            $user = Auth::user();
            
            // Check if user is banned
            if ($user->is_banned) {
                Auth::logout();
                return back()->withErrors([
                    'email' => 'Your account has been suspended. Please contact support.',
                ])->onlyInput('email');
            }

            $request->session()->regenerate();
            
            if ($user->hasRole('admin')) {
                return redirect()->intended(route('admin.dashboard'));
            }

            return redirect()->intended(route('dashboard'));
        }
        
        // Authentication failed
        return back()->withErrors([
            'email' => 'The provided credentials do not match our records.',
        ])->onlyInput('email');
    }

    public function showRegister()
    {
        return view('auth.register');
    }

    public function register(RegisterRequest $request)
    {
        $validated = $request->validated();

        // Resolve tenant from referral or domain
        $tenant = $this->resolveTenant($request);

        if (!$tenant) {
            return back()->withErrors(['email' => 'Registration is currently disabled. No tenant available.']);
        }

        $user = User::create([
            'tenant_id' => $tenant->id,
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
        ]);

        $user->assignRole('user');

        Auth::login($user);

        return redirect()->route('dashboard');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/');
    }

    protected function resolveTenant(Request $request): ?Tenant
    {
        // Check if there's a referral code in the request
        if ($request->has('ref')) {
            $referrer = User::withoutGlobalScope('tenant')->find($request->input('ref'));
            if ($referrer) {
                return $referrer->tenant;
            }
        }

        // Try to get tenant by domain
        $host = $request->getHost();
        $tenant = Tenant::where('domain', $host)->orWhere('domain', 'www.' . $host)->first();

        if ($tenant) {
            return $tenant;
        }

        // Fall back to default tenant
        return Tenant::where('is_default', true)->first() ?? Tenant::first();
    }
}
