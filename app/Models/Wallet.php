<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class Wallet extends Model
{
    use HasUuids;

    protected $fillable = ['user_id', 'tenant_id'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function balances()
    {
        return $this->hasMany(WalletBalance::class);
    }

    public function transactions()
    {
        return $this->hasMany(Transaction::class);
    }

    public function tenant()
    {
        return $this->belongsTo(Tenant::class);
    }
}
