<?php

namespace App\Models;

use App\Core\Traits\HasUid;
use Illuminate\Database\Eloquent\Model;

class Service extends Model
{
    use HasUid;
    
    protected $fillable = ['name', 'category', 'icon', 'status'];
}
