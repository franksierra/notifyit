<?php

namespace App\Components\Sms\Contracts;

interface Client
{

    /**
     * Send a new message using a view.
     *
     * @param string|array $recipients
     * @param string $message
     * @return void
     */
    public function send($recipients, $message);

    /**
     * Get the array of failed recipients.
     *
     * @return array
     */
    public function failures();
}
