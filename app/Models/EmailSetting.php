<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\EmailSetting
 *
 * @property int $id
 * @property int $app_id
 * @property string $driver
 * @property string|null $host
 * @property int|null $port
 * @property string|null $encryption
 * @property string|null $username
 * @property string|null $password
 * @property string $mail_type
 * @property string $subject_prefix
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\EmailSetting newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\EmailSetting newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\EmailSetting query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\EmailSetting whereAppId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\EmailSetting whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\EmailSetting whereDriver($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\EmailSetting whereEncryption($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\EmailSetting whereHost($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\EmailSetting whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\EmailSetting whereMailType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\EmailSetting wherePassword($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\EmailSetting wherePort($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\EmailSetting whereSubjectPrefix($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\EmailSetting whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\EmailSetting whereUsername($value)
 * @mixin \Eloquent
 */
class EmailSetting extends Model
{
    //
}
