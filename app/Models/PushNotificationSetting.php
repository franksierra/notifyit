<?php

namespace App\Models;

use App\Models\Notification\NotificationSetting;
use App\Models\Notification\NotificationType;

/**
 * App\Models\PushNotificationSetting
 *
 * @property string $id
 * @property string $credential_id
 * @property string $type
 * @property string $driver
 * @property array $config
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Credential $credential
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\PushNotificationSetting newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\PushNotificationSetting newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\PushNotificationSetting query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\PushNotificationSetting whereConfig($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\PushNotificationSetting whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\PushNotificationSetting whereCredentialId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\PushNotificationSetting whereDriver($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\PushNotificationSetting whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\PushNotificationSetting whereType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\PushNotificationSetting whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class PushNotificationSetting extends NotificationSetting
{
    /**
     * @inheritDoc
     */
    public static function settingType()
    {
        return NotificationType::PUSH;
    }

}
