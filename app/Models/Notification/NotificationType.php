<?php

namespace App\Models\Notification;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Scope;

class NotificationType implements Scope
{
    public const PUSH = 'push';
    public const EMAIL = 'email';
    public const SMS = 'sms';

    /**
     * @inheritDoc
     */
    public function apply(Builder $builder, Model $model)
    {
        if ($model instanceof Notification) {
            $builder->where('type', '=', $model->settingType());
        }
    }
}
