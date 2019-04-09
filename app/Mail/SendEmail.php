<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class SendEmail extends Mailable
{
    use Queueable, SerializesModels;

    private $details = [];

    /**
     * Create a new message instance.
     *
     * @param $details
     */
    public function __construct($details)
    {
        $this->details = $details;

    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $html = $this->details['body'];
        foreach ($this->details['embedded'] as $embedded) {
            $html = str_replace(
                'cid:' . $embedded["name"],
                "data:image/" . $embedded["format"] . "base64, " . $embedded["b64"],
                $html
            );
        }
        $this->subject($this->details['subject']);
        $this->html($html);
        $this->text($this->details['alt_body']);

        $this->buildAttachments()


//        foreach ($this->details['embedded'] as $nombre => $archivo) {
//            $this->attachData('', '', []);
//        }
        return $this;
    }
}
