<?php

namespace App\Components\Push;

use App\Components\Push\Drivers\Fcm;
use App\Components\Push\Drivers\NullDriver;
use Illuminate\Support\Manager;

class PushManager extends Manager
{

    /**
     * @inheritDoc
     */
    public function getDefaultDriver()
    {
        return $this->config->get('push.driver') ?? 'null';
    }

    /**
     * Create a Null Push driver instance.
     *
     * @return NullDriver
     */
    public function createNullDriver()
    {
        return new NullDriver();
    }

    /**
     * Create an instance of FireBaseCloudMessaging Push Driver
     *
     * @return Fcm
     */
    public function createFcmDriver()
    {
        return new Fcm($this->config["push"]);
    }
}
