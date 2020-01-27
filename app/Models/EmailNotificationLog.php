<?php

namespace App\Models;


use App\Models\Notification\NotificationLog;
use App\Models\Notification\NotificationType;

/**
 * App\Models\EmailNotificationLog
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
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\EmailNotificationLog newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\EmailNotificationLog newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\EmailNotificationLog query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\EmailNotificationLog whereAdditional($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\EmailNotificationLog whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\EmailNotificationLog whereCredentialId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\EmailNotificationLog whereException($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\EmailNotificationLog whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\EmailNotificationLog whereJobId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\EmailNotificationLog wherePayload($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\EmailNotificationLog whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\EmailNotificationLog whereType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\EmailNotificationLog whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class EmailNotificationLog extends NotificationLog
{

    public static function settingType()
    {
        return NotificationType::EMAIL;
    }
}
