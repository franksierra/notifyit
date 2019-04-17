<?php

namespace App\Jobs;

use App\Models\EmailSetting;
use Exception;
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
    public $tries = 1;

    /**
     * Create a new job instance.
     *
     * @param $details
     */
    public function __construct($details)
    {
        $this->details = $details;
    }


    /**
     * Execute the job.
     *
     * @return void
     * @throws Exception
     */
    public function handle()
    {
        /**
         * GetConfigs Based on the details
         */

        $job_config = EmailSetting::whereAppId($this->details['app_id'])->first();
        if (!$job_config) {
            $no_config_set = new Exception(
                "The app doesn't have any settings configured"
            );
            $this->fail($no_config_set);
            throw $no_config_set;
        }
        $config = [
            'driver' => $job_config->driver,
            'host' => $job_config->host,
            'port' => $job_config->port,
            'from' => [
                'address' => $this->details['from'],
                'name' => $this->details['name']
            ],
            'encryption' => $job_config->encryption,
            'username' => $job_config->username,
            'password' => $job_config->password,
            'sendmail' => '/usr/sbin/sendmail -bs',
            'pretend' => false,
        ];
        Config::set('mail', $config);

        if (!empty($job_config->subject_prefix)) {
            $this->details['subject'] = "[" . $job_config->subject_prefix . "] " . $this->details['subject'];
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
