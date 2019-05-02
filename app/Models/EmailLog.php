<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\EmailLog
 *
 * @property int $id
 * @property string $uuid
 * @property string $status
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\EmailLog newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\EmailLog newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\EmailLog query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\EmailLog whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\EmailLog whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\EmailLog whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\EmailLog whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\EmailLog whereUuid($value)
 * @mixin \Eloquent
 * @property string $uid
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\EmailLog whereUid($value)
 * @property mixed|null $data
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\EmailLog whereData($value)
 */
class EmailLog extends Model
{
    protected $fillable = ['uuid'];
    //
}
