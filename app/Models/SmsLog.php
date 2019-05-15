<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\SmsLog
 *
 * @property int $id
 * @property int $app_id
 * @property string $uuid
 * @property string $status
 * @property mixed|null $data
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\SmsLog newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\SmsLog newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\SmsLog query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\SmsLog whereAppId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\SmsLog whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\SmsLog whereData($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\SmsLog whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\SmsLog whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\SmsLog whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\SmsLog whereUuid($value)
 * @mixin \Eloquent
 */
class SmsLog extends Model
{
    protected $fillable = ['app_id', 'uuid'];

}
