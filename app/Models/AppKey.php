<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AppKey extends Model
{

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'app_id');
    }

    /**
     * Get ApiKey record by key value
     *
     * @param string $key
     * @return bool
     */
    public static function getByKey($key)
    {
        return self::where([
            'key' => $key,
            'active' => 1
        ])->first();
    }
}
