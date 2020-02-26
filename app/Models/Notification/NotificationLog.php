<?php

namespace App\Models\Notification;

abstract class NotificationLog extends Notification
{
    protected $table = 'notification_logs';

    protected $fillable = [
        'credential_id',
        'job_id',
        'status',
        'payload',
        'exception',
        'additional',
    ];

    protected $guarded = [
        'type',
    ];

    protected $hidden = [
        'credential_id',
    ];

    protected $casts = [
        'payload' => 'json',
        'exception' => 'json',
        'additional' => 'json',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];
}
