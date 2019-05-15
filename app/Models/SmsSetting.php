<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\SmsSetting
 *
 * @property int $id
 * @property int $app_id
 * @property string $endpoint
 * @property string $type
 * @property mixed $payload
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\SmsSetting newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\SmsSetting newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\SmsSetting query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\SmsSetting whereAppId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\SmsSetting whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\SmsSetting whereEndpoint($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\SmsSetting whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\SmsSetting wherePayload($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\SmsSetting whereType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\SmsSetting whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class SmsSetting extends Model
{
    //
}
