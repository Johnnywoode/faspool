<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class SubAccountController extends Controller
{
    /**
     * Display a listing of sub-accounts.
     */
    public function index()
    {
        $subAccounts = User::where('parent_id', Auth::id())
            ->latest()
            ->paginate(10);
            
        return view('subaccounts.index', compact('subAccounts'));
    }

    /**
     * Store a newly created sub-account.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
        ]);

        $parent = Auth::user();

        $subAccount = User::create([
            'uid' => Str::uuid(),
            'parent_id' => $parent->id,
            'tenant_id' => $parent->tenant_id,
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'status' => 'active',
        ]);

        // Assign the same role as parent or a default 'user' role
        $subAccount->assignRole('user');

        // Create wallet for sub-account
        $subAccount->wallet()->create(['tenant_id' => $parent->tenant_id]);

        return back()->with('success', 'Sub-account created successfully.');
    }

    /**
     * Transfer balance from parent to sub-account.
     */
    public function transfer(Request $request, User $subAccount)
    {
        if ($subAccount->parent_id !== Auth::id()) {
            abort(403);
        }

        $request->validate([
            'amount' => 'required|numeric|min:1',
        ]);

        $parent = Auth::user();
        $amount = (float) $request->amount;

        $parentBalance = $parent->wallet ? $parent->wallet->balance : 0;

        if ($parentBalance < $amount) {
            return back()->with('error', 'Insufficient balance in main wallet.');
        }

        \Illuminate\Support\Facades\DB::transaction(function () use ($parent, $subAccount, $amount) {
            // Deduct from parent
            $parent->wallet->balances()->first()->decrement('balance', $amount);
            $parent->wallet->transactions()->create([
                'amount' => $amount,
                'type' => 'debit',
                'description' => 'Transfer to sub-account: ' . $subAccount->name,
                'status' => 'completed',
            ]);

            // Add to sub-account
            $subAccountWallet = $subAccount->wallet ?? $subAccount->wallet()->create(['tenant_id' => $parent->tenant_id]);
            $subAccountBalance = $subAccountWallet->balances()->firstOrCreate(['currency' => 'GHS'], ['balance' => 0]);
            $subAccountBalance->increment('balance', $amount);
            
            $subAccountWallet->transactions()->create([
                'amount' => $amount,
                'type' => 'credit',
                'description' => 'Transfer from main account',
                'status' => 'completed',
            ]);
        });

        return back()->with('success', 'Balance transferred successfully.');
    }
}
