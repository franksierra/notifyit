<?php

namespace App\Components\Sms\Contracts;

interface Driver
{
    /**
     * Send a new message to its recipients
     *
     * @param string|array $recipients
     * @param string $message
     * @param array $failedRecipients
     * @return void
     */
    public function send($recipients, $message, &$failedRecipients = []);
}
