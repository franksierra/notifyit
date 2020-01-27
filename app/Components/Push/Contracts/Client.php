<?php

namespace App\Components\Push\Contracts;

interface Client
{

    /**
     * Send a new push using a view.
     *
     * @param string|array $recipients
     * @param string $payload
     * @return void
     */
    public function send($recipients, $payload);

    /**
     * Get the array of failed recipients.
     *
     * @return array
     */
    public function failures();
}
