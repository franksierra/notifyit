<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AppKey extends Model
{
    const EVENT_NAME_CREATED     = 'created';
    const EVENT_NAME_ACTIVATED   = 'activated';
    const EVENT_NAME_DEACTIVATED = 'deactivated';
    const EVENT_NAME_DELETED     = 'deleted';

    protected static $nameRegex = '/^[a-z0-9-]{1,255}$/';


    /**
     * Get ApiKey record by key value
     *
     * @param string $key
     * @return bool
     */
    public static function getByKey($key)
    {
        return self::where([
            'key'    => $key,
            'active' => 1
        ])->first();
    }
}
