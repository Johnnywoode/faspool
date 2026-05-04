<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class ReferralController extends Controller
{
    /**
     * Display the referral dashboard for the user.
     */
    public function index()
    {
        $user = Auth::user();
        
        // Ensure user has a referral code
        if (!$user->referral_code) {
            $user->update(['referral_code' => strtoupper(Str::random(10))]);
        }

        $referrals = \App\Models\User::where('referred_by', $user->id)
            ->latest()
            ->paginate(10);
            
        $referralLink = route('register', ['ref' => $user->referral_code]);
        
        // Calculate earnings (mock logic for now)
        $totalEarnings = $user->wallet ? $user->wallet->transactions()->where('type', 'credit')->where('description', 'like', '%Referral%')->sum('amount') : 0;

        return view('referrals.index', compact('user', 'referrals', 'referralLink', 'totalEarnings'));
    }
}
