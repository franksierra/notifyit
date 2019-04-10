<?php

namespace App\Jobs;

use App\Models\EmailSetting;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Message;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Mail;

class SendEmailJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $details;

    /**
     * The number of times the job may be attempted.
     *
     * @var int
     */
    public $tries = 3;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($details)
    {
        $this->details = $details;
    }


    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        /**
         * GetConfigs Based on the details
         */

        $email_conf = EmailSetting::whereAppId($this->details['app_id'])->first();
        if (!$email_conf) {
            $no_config_set = new \Exception(
                "The app doesn't have any email settings set"
            );
            $this->fail($no_config_set);
        }
        $config = [
            'driver' => $email_conf->driver,
            'host' => $email_conf->host,
            'port' => $email_conf->port,
            'from' => [
                'address' => $this->details['from'],
                'name' => $this->details['name']
            ],
            'encryption' => $email_conf->encryption,
            'username' => $email_conf->username,
            'password' => $email_conf->password,
            'sendmail' => '/usr/sbin/sendmail -bs',
            'pretend' => false,
        ];
        Config::set('mail', $config);

        if (!empty($email_conf->subject_prefix)) {
            $this->details['subject'] = "[" . $email_conf->subject_prefix . "] " . $this->details['subject'];
        }
        Mail::send([], [], function (Message $message) {
            $message->to($this->details['to']);
            $message->cc($this->details['cc']);
            $message->bcc($this->details['bcc']);
            $message->subject($this->details['subject']);

            foreach ($this->details['embedded'] as $embedded) {
                $newCID = $message->embedData(
                    base64_decode($embedded["b64"]),
                    $embedded["name"] . "." . $embedded["format"],
                    'image/' . $embedded["format"]
                );
                $this->details['body'] = str_replace(
                    "cid:" . $embedded["name"],
                    $newCID,
                    $this->details['body']
                );
            }
            $message->addPart($this->details['body'], "text/html", "utf-8");
            $message->addpart($this->details['alt_body'], "text/plain", "utf-8");

            foreach ($this->details['attachments'] as $attachment) {
                $message->attachData(
                    base64_decode($attachment["b64"]),
                    $attachment["name"] . "." . $attachment["format"]
                );
            }

        });
    }
}
