<?php

namespace App\Models\Notification;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class NotificationCredential
{
    public function apply(Builder $builder, Model $model)
    {
        if ($model instanceof Notification) {
            if ($model->credential != null) {
                $builder->where('credential_id', '=', $model->credential);
            }
        }
    }
}
