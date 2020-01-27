<?php

namespace App\Components\Push\Contracts;

interface Driver
{
    /**
     * Send a new push to its recipients
     *
     * @param string|array $recipients
     * @param string $payload
     * @param array $failedRecipients
     * @return void
     */
    public function send($recipients, $payload, &$failedRecipients = []);

}
