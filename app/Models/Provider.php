<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Provider extends Model
{
    protected $fillable = ['name', 'adapter', 'config', 'is_active'];
    protected $casts = ['config' => 'json', 'is_active' => 'boolean'];
}
