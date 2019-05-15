<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\PushLog
 *
 * @property int $id
 * @property int $app_id
 * @property string $uuid
 * @property string $status
 * @property mixed|null $data
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\PushLog newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\PushLog newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\PushLog query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\PushLog whereAppId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\PushLog whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\PushLog whereData($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\PushLog whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\PushLog whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\PushLog whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\PushLog whereUuid($value)
 * @mixin \Eloquent
 */
class PushLog extends Model
{
    protected $fillable = ['app_id', 'uuid'];

}
