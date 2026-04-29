<?php

namespace App\Core\Traits;

use Illuminate\Database\Eloquent\Builder;

trait BelongsToTenant
{
    /**
     * Boot the BelongsToTenant trait.
     */
    protected static function bootBelongsToTenant()
    {
        static::creating(function ($model) {
            if (empty($model->tenant_id) && app()->bound('tenant')) {
                $model->tenant_id = app('tenant')->id;
            }
        });

        // static::addGlobalScope('tenant', function (Builder $builder) {
        //     if (app()->bound('tenant')) {
        //         $builder->where('tenant_id', app('tenant')->id);
        //     }
        // });
    }

    /**
     * Relationship to the Tenant model.
     */
    public function tenant()
    {
        return $this->belongsTo(\App\Models\Tenant::class);
    }
}
