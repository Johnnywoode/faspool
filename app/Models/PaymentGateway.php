<?php

namespace App\Models;

use App\Core\Traits\HasUid;
use Illuminate\Database\Eloquent\Model;

class PaymentGateway extends Model
{
    use HasUid;

    protected $fillable = ['name', 'slug', 'adapter', 'config', 'is_active'];

    protected $casts = [
        'config' => 'json',
        'is_active' => 'boolean',
    ];
}
