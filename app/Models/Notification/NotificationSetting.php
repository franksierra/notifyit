<?php

namespace App\Models\Notification;

use App\Models\Credential;
use Illuminate\Support\Facades\Crypt;

abstract class NotificationSetting extends Notification
{
    protected $table = 'notification_settings';

    protected $fillable = [
        'credential_id',
        'driver',
        'config',
    ];

    protected $guarded = [
        'type',
    ];

    protected $hidden = [
        'credential_id',
    ];

    protected $casts = [
        'config' => 'json',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    public function setConfigAttribute($config)
    {
        $config = json_encode($config);
        $this->attributes['config'] = $config; //Crypt::encryptString($config);
    }

    public function getConfigAttribute()
    {
//        $config = Crypt::decryptString($this->attributes['config']);
        return $this->castAttribute('config', $this->attributes['config']);
    }
}
