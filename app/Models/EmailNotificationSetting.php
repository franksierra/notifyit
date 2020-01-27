<?php

namespace App\Models;

use App\Models\Notification\NotificationSetting;
use App\Models\Notification\NotificationType;

/**
 * App\Models\EmailNotificationSetting
 *
 * @property string $id
 * @property string $credential_id
 * @property string $type
 * @property string $driver
 * @property array $config
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Credential $credential
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\EmailNotificationSetting newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\EmailNotificationSetting newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\EmailNotificationSetting query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\EmailNotificationSetting whereConfig($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\EmailNotificationSetting whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\EmailNotificationSetting whereCredentialId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\EmailNotificationSetting whereDriver($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\EmailNotificationSetting whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\EmailNotificationSetting whereType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\EmailNotificationSetting whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class EmailNotificationSetting extends NotificationSetting
{
    /**
     * @inheritDoc
     */
    public static function settingType()
    {
        return NotificationType::EMAIL;
    }
}
