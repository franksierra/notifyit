<?php

namespace App\Components\Push\Drivers;


use App\Components\Push\Contracts\Driver;

class NullDriver implements Driver
{

    /** @var array */
    private $sentNotifications = [];

    /**
     * @inheritDoc
     */
    public function send($recipients, $payload, &$failedRecipients = [])
    {
        if (!array($recipients)) {
            $recipients[] = $recipients;
        }
        foreach ($recipients as $to) {
            $this->sentNotifications[] = [
                'to' => $to
            ];
        }
    }
}
