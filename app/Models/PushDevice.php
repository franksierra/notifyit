<?php

namespace App\Models;

use App\Traits\UuidKey;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

/**
 * App\Models\PushDevice
 *
 * @property string $id
 * @property string $credential_id
 * @property string $platform
 * @property string $uuid
 * @property string|null $identity
 * @property string|null $regid
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @method static Builder|PushDevice newModelQuery()
 * @method static Builder|PushDevice newQuery()
 * @method static Builder|PushDevice query()
 * @method static Builder|PushDevice whereCreatedAt($value)
 * @method static Builder|PushDevice whereCredentialId($value)
 * @method static Builder|PushDevice whereId($value)
 * @method static Builder|PushDevice whereIdentity($value)
 * @method static Builder|PushDevice wherePlatform($value)
 * @method static Builder|PushDevice whereRegid($value)
 * @method static Builder|PushDevice whereUpdatedAt($value)
 * @method static Builder|PushDevice whereUuid($value)
 * @mixin /Eloquent
 */
class PushDevice extends Model
{
    use UuidKey;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'credential_id',
        'platform',
        'uuid',
        'identity',
        'regid'
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];
}
