<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Filesystem\FileNotFoundException;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Message;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Storage;

class DynamicEmail extends Mailable
{
    use Queueable, SerializesModels;

    private $email;

    /**
     * Create a new message instance.
     *
     * @param $email
     */
    public function __construct($email)
    {
        $this->email = $email;
    }

    /**
     * @param Message $message
     * @return $this|Mailable
     * @throws FileNotFoundException
     */
    protected function runCallbacks($message)
    {
        $body = Storage::disk('local')->get($this->email['body']);
        $altBody = Storage::disk('local')->get($this->email['alt_body']);
        foreach ($this->email['embedded'] as $embedded) {
            if (Storage::disk('local')->exists($embedded['file'])) {
                $newCID = $message->embedData(
                    Storage::disk('local')->get($embedded['file']),
                    $embedded["name"] . "." . $embedded["format"]
                );
                $body = str_replace(
                    "cid:" . $embedded["name"],
                    $newCID,
                    $this->email['body']
                );
            }
        }
        $message->addPart($body, "text/html", "utf-8");
        $message->addpart($altBody, "text/plain", "utf-8");
        return $this;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view('emails.dynamic');
    }
}
