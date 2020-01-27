<?php

namespace App\Models;


use App\Models\Notification\NotificationLog;
use App\Models\Notification\NotificationType;

/**
 * App\Models\SmsNotificationLog
 *
 * @property string $id
 * @property string $credential_id
 * @property string $type
 * @property string $job_id
 * @property string $status
 * @property array|null $payload
 * @property array|null $exception
 * @property string|null $additional
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Credential $credential
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\SmsNotificationLog newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\SmsNotificationLog newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\SmsNotificationLog query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\SmsNotificationLog whereAdditional($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\SmsNotificationLog whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\SmsNotificationLog whereCredentialId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\SmsNotificationLog whereException($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\SmsNotificationLog whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\SmsNotificationLog whereJobId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\SmsNotificationLog wherePayload($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\SmsNotificationLog whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\SmsNotificationLog whereType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\SmsNotificationLog whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class SmsNotificationLog extends NotificationLog
{

    public static function settingType()
    {
        return NotificationType::SMS;
    }
}
