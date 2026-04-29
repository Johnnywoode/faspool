<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Tenant;
use Spatie\Permission\Models\Role;
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
        $users = User::with('roles', 'tenant')->orderBy('created_at', 'desc')->paginate(20);
        $roles = Role::all();
        $tenants = Tenant::all();
        return view('admin.users.index', compact('users', 'roles', 'tenants'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $roles = Role::all();
        $tenants = Tenant::all();
        return view('admin.users.create', compact('roles', 'tenants'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'tenant_id' => 'required|exists:tenants,id',
            'role' => 'required|exists:roles,name',
        ]);

        $user = User::create([
            'tenant_id' => $validated['tenant_id'],
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => $validated['password'], // Let 'hashed' cast handle this
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
        return view('admin.users.show', compact('user'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(User $user)
    {
        $roles = Role::all();
        $tenants = Tenant::all();
        return view('admin.users.edit', compact('user', 'roles', 'tenants'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, User $user)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique('users')->ignore($user->id)],
            'tenant_id' => 'required|exists:tenants,id',
            'role' => 'required|exists:roles,name',
            'password' => 'nullable|string|min:8|confirmed',
        ]);

        $user->update([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'tenant_id' => $validated['tenant_id'],
        ]);

        if (!empty($validated['password'])) {
            $user->update(['password' => $validated['password']]); // Let 'hashed' cast handle this
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
            'amount' => 'required|numeric|decimal:0,2',
            'type' => 'required|in:credit,debit',
            'description' => 'nullable|string|max:255',
        ]);

        $wallet = $user->wallet;

        if (!$wallet) {
            $wallet = $user->wallet()->create();
        }

        $walletBalance = $wallet->balances()->first();
        if (!$walletBalance) {
            $walletBalance = $wallet->balances()->create([
                'currency' => 'USD',
                'balance' => 0
            ]);
        }

        $amount = abs($request->amount);
        $description = $request->description ?: ($request->type === 'credit' ? 'Admin credit' : 'Admin debit');

        if ($request->type === 'credit') {
            $walletBalance->increment('balance', $amount);
            $wallet->transactions()->create([
                'amount' => $amount,
                'type' => 'credit',
                'description' => $description,
                'status' => 'completed'
            ]);
        } else {
            if ($walletBalance->balance < $amount) {
                return back()->with('error', 'Insufficient wallet balance.');
            }
            $walletBalance->decrement('balance', $amount);
            $wallet->transactions()->create([
                'amount' => $amount,
                'type' => 'debit',
                'description' => $description,
                'status' => 'completed'
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
}
