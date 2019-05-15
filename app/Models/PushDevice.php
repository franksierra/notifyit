<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\PushDevice
 *
 * @property int $id
 * @property int $app_id
 * @property string $platform
 * @property string $uuid
 * @property string $identity
 * @property string $regid
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\PushDevice newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\PushDevice newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\PushDevice query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\PushDevice whereAppId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\PushDevice whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\PushDevice whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\PushDevice whereIdentity($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\PushDevice wherePlatform($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\PushDevice whereRegid($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\PushDevice whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\PushDevice whereUuid($value)
 * @mixin \Eloquent
 */
class PushDevice extends Model
{
    protected $fillable = ['app_id', 'platform', 'uid', 'identity', 'regid'];
}
