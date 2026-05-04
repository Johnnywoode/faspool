<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Provider extends Model
{
    protected $fillable = [
        'name',
        'slug',
        'adapter',
        'config',
        'status',
        'is_active',
    ];

    protected $casts = [
        'config'    => 'array',
        'is_active' => 'boolean',
    ];

    /**
     * Orders placed through this provider.
     */
    public function orders()
    {
        return $this->hasMany(Order::class);
    }
}
