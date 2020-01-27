<?php

namespace App\Models;

use App\Traits\UuidKey;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\Credential
 *
 * @property string $id
 * @property string $project_id
 * @property bool $production
 * @property bool $prefix
 * @property string $prefix_value
 * @property string $api_key
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\EmailNotificationLog $emailLog
 * @property-read \App\Models\EmailNotificationSetting $emailSetting
 * @property-read \App\Models\Project $project
 * @property-read \App\Models\PushNotificationLog $pushLog
 * @property-read \App\Models\PushNotificationSetting $pushSetting
 * @property-read \App\Models\SmsNotificationLog $smsLog
 * @property-read \App\Models\SmsNotificationSetting $smsSetting
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Credential newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Credential newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Credential query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Credential whereApiKey($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Credential whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Credential whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Credential wherePrefix($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Credential wherePrefixValue($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Credential whereProduction($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Credential whereProjectId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Credential whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class Credential extends Model
{
    use UuidKey;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'project_id', 'api_key',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'production' => 'boolean',
        'prefix' => 'boolean',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    public function project()
    {
        return $this->belongsTo(Project::class);
    }

    public function emailSetting()
    {
        return $this->hasOne(EmailNotificationSetting::class);
    }

    public function pushSetting()
    {
        return $this->hasOne(PushNotificationSetting::class);
    }

    public function smsSetting()
    {
        return $this->hasOne(SmsNotificationSetting::class);
    }

    public function emailLog()
    {
        return $this->hasOne(EmailNotificationLog::class);
    }

    public function pushLog()
    {
        return $this->hasOne(PushNotificationLog::class);
    }

    public function smsLog()
    {
        return $this->hasOne(SmsNotificationLog::class);
    }
}
