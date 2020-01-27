<?php

namespace App\Models;


use App\Models\Notification\NotificationLog;
use App\Models\Notification\NotificationType;

/**
 * App\Models\PushNotificationLog
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
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\PushNotificationLog newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\PushNotificationLog newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\PushNotificationLog query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\PushNotificationLog whereAdditional($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\PushNotificationLog whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\PushNotificationLog whereCredentialId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\PushNotificationLog whereException($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\PushNotificationLog whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\PushNotificationLog whereJobId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\PushNotificationLog wherePayload($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\PushNotificationLog whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\PushNotificationLog whereType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\PushNotificationLog whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class PushNotificationLog extends NotificationLog
{

    public static function settingType()
    {
        return NotificationType::PUSH;
    }
}
