<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\PushSetting
 *
 * @property int $id
 * @property int $app_id
 * @property string $endpoint
 * @property string $api_key
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\PushSetting newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\PushSetting newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\PushSetting query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\PushSetting whereApiKey($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\PushSetting whereAppId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\PushSetting whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\PushSetting whereEndpoint($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\PushSetting whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\PushSetting whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class PushSetting extends Model
{
    //
}
