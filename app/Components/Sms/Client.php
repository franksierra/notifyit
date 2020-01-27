<?php

namespace App\Components\Sms;

use App\Components\Sms\Contracts\Client as ClientContract;
use App\Components\Sms\Contracts\Driver;

class Client implements ClientContract
{
    /**
     * Driver to use.
     *
     * @var Driver
     */
    private $driver;

    /**
     * Array of failed recipients.
     *
     * @var array
     */
    private $failedRecipients = [];

    /**
     * Messenger constructor.
     * @param Driver $driver
     */
    public function __construct(Driver $driver)
    {
        $this->driver = $driver;
    }

    /**
     * @inheritDoc
     */
    public function send($recipients, $message)
    {
        $this->driver->send($recipients, $message, $this->failedRecipients);
    }

    /**
     * @inheritDoc
     */
    public function failures()
    {
        return $this->failedRecipients;
    }
}
