<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RequestLog extends Model
{
    protected $fillable = [
        'origin',
        'app_id',
        'user_id',
        'method',
        'uri',
        'headers',
        'params',
        'ip',
        'status_code',
        'response',
        'exec_time',
    ];
    //
}
