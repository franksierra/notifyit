<?php

namespace App\Models\Notification;

use App\Models\Credential;
use App\Traits\UuidKey;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Crypt;

abstract class Notification extends Model
{
    use UuidKey;

    abstract public static function settingType();

    protected static function boot()
    {
        static::addGlobalScope(new NotificationType());
        self::creating(function (Model $model) {
            $model->attributes['type'] = $model->settingType();
        });
        self::updating(function (Model $model) {
            $model->attributes['type'] = $model->settingType();
        });
        parent::boot();
    }

    public function credential()
    {
        return $this->belongsTo(Credential::class);
    }
}
