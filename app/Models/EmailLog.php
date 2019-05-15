<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\EmailLog
 *
 * @property int $id
 * @property int $app_id
 * @property string $uuid
 * @property string $status
 * @property mixed|null $data
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\EmailLog newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\EmailLog newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\EmailLog query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\EmailLog whereAppId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\EmailLog whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\EmailLog whereData($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\EmailLog whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\EmailLog whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\EmailLog whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\EmailLog whereUuid($value)
 * @mixin \Eloquent
 */
class EmailLog extends Model
{
    protected $fillable = ['app_id', 'uuid'];

}
