<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Core\Traits\HasUid;

class Order extends Model
{
    use SoftDeletes, HasUid;

    protected $fillable = [
        'user_id',
        'tenant_id',
        'provider_id',
        'service_id',
        'country_id',
        'number',
        'status',
        'cost',
        'price',
        'currency',
        'external_id',
        'sms_text',
        'expires_at',
    ];

    protected $casts = [
        'expires_at' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function tenant()
    {
        return $this->belongsTo(Tenant::class);
    }

    public function provider()
    {
        return $this->belongsTo(Provider::class);
    }

    public function service()
    {
        return $this->belongsTo(Service::class);
    }

    public function country()
    {
        return $this->belongsTo(Country::class);
    }
}
