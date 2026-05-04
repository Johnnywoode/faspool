<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class Tenant extends Model
{
    use SoftDeletes, HasUuids;

    protected $fillable = [
        'name',
        'domain',
        'api_key',
        'settings',
        'is_default',
        'status',
    ];

    protected $casts = [
        'settings' => 'json',
    ];

    /**
     * Users belonging to this tenant.
     */
    public function users()
    {
        return $this->hasMany(User::class);
    }

    /**
     * Orders belonging to this tenant.
     */
    public function orders()
    {
        return $this->hasMany(Order::class);
    }

    /**
     * Wallets belonging to this tenant.
     */
    public function wallets()
    {
        return $this->hasMany(Wallet::class);
    }

    public function walletBalances()
    {
        return $this->hasManyThrough(WalletBalance::class, Wallet::class);
    }

    public function getTotalWalletBalanceAttribute()
    {
        return $this->walletBalances()->sum('balance');
    }
}
