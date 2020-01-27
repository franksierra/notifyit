<?php

namespace App\Components\Sms\Drivers;


use App\Components\Sms\Contracts\Driver;

class NullDriver implements Driver
{

    /** @var array */
    private $sentMessages = [];

    /**
     * @inheritDoc
     */
    public function send($recipients, $message, &$failedRecipients = [])
    {
        if (!array($recipients)) {
            $recipients[] = $recipients;
        }
        foreach ($recipients as $to) {
            $this->sentMessages[] = [
                'to' => $to,
                'message' => $message,
            ];
        }
    }
}
