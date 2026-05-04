<?php

namespace App\Models;

use App\Core\Traits\HasUid;
use Illuminate\Database\Eloquent\Model;

class Country extends Model
{
    use HasUid;
    
    protected $fillable = ['name', 'iso_code', 'region', 'flag_url', 'status'];
}
