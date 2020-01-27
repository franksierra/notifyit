<?php

namespace App\Models;

use App\Models\Notification\NotificationSetting;
use App\Models\Notification\NotificationType;

/**
 * App\Models\SmsNotificationSetting
 *
 * @property string $id
 * @property string $credential_id
 * @property string $type
 * @property string $driver
 * @property array $config
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Credential $credential
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\SmsNotificationSetting newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\SmsNotificationSetting newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\SmsNotificationSetting query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\SmsNotificationSetting whereConfig($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\SmsNotificationSetting whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\SmsNotificationSetting whereCredentialId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\SmsNotificationSetting whereDriver($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\SmsNotificationSetting whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\SmsNotificationSetting whereType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\SmsNotificationSetting whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class SmsNotificationSetting extends NotificationSetting
{
    /**
     * @inheritDoc
     */
    public static function settingType()
    {
        return NotificationType::SMS;
    }

}
