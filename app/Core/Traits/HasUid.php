<?php


namespace App\Core\Traits;



trait HasUid
{
    public static function bootHasUid()
    {
        static::creating(function ($model) {
            if (empty($model->uid)) {
                $model->uid = uniqid();
            }
        });
    }


    public static function findByUid($uid)
    {
        return self::where('uid', '=', $uid)->first();
    }

    public function getUid(): ?string
    {
        return $this->uid;
    }

    /**
     * get route key by uid
     *
     * @return string
     */
    public function getRouteKeyName()
    {
        return 'uid';
    }
}
