<?php

namespace App\Components\Sms;

use App\Components\Sms\Drivers\NullDriver;
use App\Components\Sms\Drivers\EclipSoftDriver;
use Illuminate\Support\Manager;

class SmsManager extends Manager
{
    /**
     * @inheritDoc
     */
    public function getDefaultDriver()
    {
        return $this->config->get('sms.driver') ?? 'null';
    }


    /**
     * Create an instance of EclipSoft sms Driver
     *
     * @return EclipSoftDriver
     */
    public function createEclipSoftDriver()
    {
        return new EclipSoftDriver($this->config["sms"]);
    }

    /**
     * Create a Null SMS driver instance.
     *
     * @return NullDriver
     */
    public function createNullDriver()
    {
        return new NullDriver();
    }

}
