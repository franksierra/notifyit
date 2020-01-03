<?php

namespace App\Listeners;

use Illuminate\Mail\Events\MessageSent;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class SentEmailLogger
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param MessageSent $event
     * @return void
     */
    public function handle(MessageSent $event)
    {
        $uuid = $event->data['uuid'] ?? Str::random(16);
        $rawEmail = $event->message->toString();

        $name = 'mails/' . $uuid . '.eml';

        Storage::disk('public')->makeDirectory('mails/');
        Storage::disk('public')->put($name, $rawEmail);

    }
}
