<?php

namespace App\Models;

use App\Traits\UuidKey;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\Request
 *
 * @property string $id
 * @property string $origin
 * @property string|null $credential_id
 * @property string|null $user_id
 * @property string $method
 * @property string $uri
 * @property string $ip
 * @property array $headers
 * @property array $params
 * @property int|null $status_code
 * @property array|null $response
 * @property float|null $exec_time
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Request newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Request newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Request query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Request whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Request whereCredentialId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Request whereExecTime($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Request whereHeaders($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Request whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Request whereIp($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Request whereMethod($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Request whereOrigin($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Request whereParams($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Request whereResponse($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Request whereStatusCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Request whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Request whereUri($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Request whereUserId($value)
 * @mixin \Eloquent
 */
class Request extends Model
{
    use UuidKey;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'origin',
        'credential_id',
        'user_id',
        'method',
        'uri',
        'ip',
        'headers',
        'params',
        'status_code',
        'response',
        'exec_time',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'headers' => 'json',
        'params' => 'json',
        'response' => 'json',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];
}

