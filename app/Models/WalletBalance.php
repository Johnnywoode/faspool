<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class WalletBalance extends Model
{
    protected $fillable = ['wallet_id', 'currency', 'balance'];

    public function wallet()
    {
        return $this->belongsTo(Wallet::class);
    }
}
