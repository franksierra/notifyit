<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\SMSLog
 *
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\SmsLog newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\SmsLog newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\SmsLog query()
 * @mixin \Eloquent
 * @property int $id
 * @property string $uuid
 * @property string $status
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\SmsLog whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\SmsLog whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\SmsLog whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\SmsLog whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\SmsLog whereUuid($value)
 * @property mixed|null $data
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\SmsLog whereData($value)
 */
class SmsLog extends Model
{
    protected $fillable = ['uuid'];
}
