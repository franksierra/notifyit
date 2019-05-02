<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\PushLog
 *
 * @property int $id
 * @property string $uid
 * @property string $status
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\PushLog newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\PushLog newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\PushLog query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\PushLog whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\PushLog whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\PushLog whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\PushLog whereUid($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\PushLog whereUpdatedAt($value)
 * @mixin \Eloquent
 * @property string $uuid
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\PushLog whereUuid($value)
 * @property mixed|null $data
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\PushLog whereData($value)
 */
class PushLog extends Model
{
    protected $fillable = ['uuid'];
}
