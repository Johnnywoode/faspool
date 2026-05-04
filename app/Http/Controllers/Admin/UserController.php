<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Tenant;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $users   = User::with('roles', 'tenant')->orderBy('created_at', 'desc')->paginate(20);
        $roles   = Role::all();
        $tenants = Tenant::all();
        return view('admin.users.index', compact('users', 'roles', 'tenants'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $roles   = Role::all();
        $tenants = Tenant::all();
        return view('admin.users.create', compact('roles', 'tenants'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name'      => 'required|string|max:255',
            'email'     => 'required|string|email|max:255|unique:users',
            'password'  => 'required|string|min:8|confirmed',
            'tenant_id' => 'required|exists:tenants,id',
            'role'      => 'required|exists:roles,name',
        ]);

        $user = User::create([
            'tenant_id' => $validated['tenant_id'],
            'name'      => $validated['name'],
            'email'     => $validated['email'],
            'password'  => $validated['password'], // 'hashed' cast handles this
        ]);

        $user->assignRole($validated['role']);

        return redirect()->route('admin.users.index')
            ->with('success', 'User created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(User $user)
    {
        $user->load('roles', 'tenant', 'wallet');
        return view('admin.users.show', compact('user'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(User $user)
    {
        $roles   = Role::all();
        $tenants = Tenant::all();
        return view('admin.users.edit', compact('user', 'roles', 'tenants'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, User $user)
    {
        $validated = $request->validate([
            'name'      => 'required|string|max:255',
            'email'     => ['required', 'string', 'email', 'max:255', Rule::unique('users')->ignore($user->id)],
            'tenant_id' => 'required|exists:tenants,id',
            'role'      => 'required|exists:roles,name',
            'password'  => 'nullable|string|min:8|confirmed',
        ]);

        $user->update([
            'name'      => $validated['name'],
            'email'     => $validated['email'],
            'tenant_id' => $validated['tenant_id'],
        ]);

        if (!empty($validated['password'])) {
            $user->update(['password' => $validated['password']]); // 'hashed' cast handles this
        }

        $user->syncRoles([$validated['role']]);

        return redirect()->route('admin.users.index')
            ->with('success', 'User updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $user)
    {
        if ($user->id === auth()->id()) {
            return back()->with('error', 'You cannot delete your own account.');
        }

        $user->delete();

        return redirect()->route('admin.users.index')
            ->with('success', 'User deleted successfully.');
    }

    /**
     * Adjust user wallet balance.
     */
    public function adjustWallet(Request $request, User $user)
    {
        $request->validate([
            'amount'      => 'required|numeric|decimal:0,2',
            'type'        => 'required|in:credit,debit',
            'description' => 'nullable|string|max:255',
        ]);

        $wallet = $user->wallet;

        if (!$wallet) {
            $wallet = $user->wallet()->create(['tenant_id' => $user->tenant_id]);
        }

        $walletBalance = $wallet->balances()->first();
        if (!$walletBalance) {
            $walletBalance = $wallet->balances()->create([
                'currency' => 'GHS',
                'balance'  => 0,
            ]);
        }

        $amount      = abs($request->amount);
        $description = $request->description ?: ($request->type === 'credit' ? 'Admin credit' : 'Admin debit');

        if ($request->type === 'credit') {
            $walletBalance->increment('balance', $amount);
            $wallet->transactions()->create([
                'amount'      => $amount,
                'type'        => 'credit',
                'description' => $description,
                'status'      => 'completed',
            ]);
        } else {
            if ($walletBalance->balance < $amount) {
                return back()->with('error', 'Insufficient wallet balance.');
            }
            $walletBalance->decrement('balance', $amount);
            $wallet->transactions()->create([
                'amount'      => $amount,
                'type'        => 'debit',
                'description' => $description,
                'status'      => 'completed',
            ]);
        }

        return back()->with('success', 'Wallet adjusted successfully.');
    }

    /**
     * Toggle user ban status.
     */
    public function toggleBan(User $user)
    {
        if ($user->id === auth()->id()) {
            return back()->with('error', 'You cannot ban yourself.');
        }

        $user->update(['is_banned' => !$user->is_banned]);

        $status = $user->is_banned ? 'banned' : 'unbanned';
        return back()->with('success', "User {$status} successfully.");
    }

    /**
     * Assign a role to the user.
     */
    public function assignRole(Request $request, User $user)
    {
        $request->validate([
            'role' => 'required|string|exists:roles,name',
        ]);

        $user->assignRole($request->role);

        return back()->with('success', "Role '{$request->role}' assigned to {$user->name}.");
    }

    /**
     * Remove a role from the user.
     */
    public function removeRole(Request $request, User $user)
    {
        $request->validate([
            'role' => 'required|string|exists:roles,name',
        ]);

        $user->removeRole($request->role);

        return back()->with('success', "Role '{$request->role}' removed from {$user->name}.");
    }

    /**
     * Toggle two-factor authentication for the user.
     */
    public function toggle2FA(User $user)
    {
        // TODO: integrate with Laravel Fortify or custom 2FA
        $current = $user->two_factor_confirmed_at ?? null;

        if ($current) {
            $user->forceFill([
                'two_factor_secret'       => null,
                'two_factor_recovery_codes' => null,
                'two_factor_confirmed_at' => null,
            ])->save();

            return back()->with('success', 'Two-factor authentication disabled for ' . $user->name . '.');
        }

        return back()->with('info', 'User must enable 2FA from their own account settings.');
    }

    /**
     * Show permissions management page for the user.
     */
    public function permissions(User $user)
    {
        $allPermissions  = Permission::all()->groupBy(function ($p) {
            return explode(' ', $p->name)[0] ?? 'other';
        });
        $userPermissions = $user->getAllPermissions()->pluck('name');

        return view('admin.users.permissions', compact('user', 'allPermissions', 'userPermissions'));
    }

    /**
     * Sync direct permissions for the user.
     */
    public function syncPermissions(Request $request, User $user)
    {
        $request->validate([
            'permissions'   => 'nullable|array',
            'permissions.*' => 'string|exists:permissions,name',
        ]);

        $user->syncPermissions($request->permissions ?? []);

        return back()->with('success', 'Permissions updated for ' . $user->name . '.');
    }
}
