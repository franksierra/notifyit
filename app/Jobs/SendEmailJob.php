<?php

namespace App\Jobs;

use App\Models\EmailLog;
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
    public $tries = 1;

    /**
     * Create a new job instance.
     *
     * @param $details
     */
    public function __construct($details)
    {
        $this->details = $details;
        self::onQueue('emails');
    }

    /**
     * Execute the job.
     *
     * @return array
     * @throws Exception
     */
    public function handle()
    {

        /**
         * GetConfigs Based on the details
         */
        $email = EmailLog::where('uuid', $this->details['uuid'])->first();

        $job_config = EmailSetting::whereAppId($this->details['app_id'])->first();
        if (!$job_config) {
            $no_config_set = new Exception(
                "The app doesn't have any settings configured to send Emails"
            );
            $this->fail($no_config_set);

            $email->status = 'failed';
            $email->data = json_encode([
                'message' => $no_config_set->getMessage()
            ]);
            $email->save();
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
            'pretend' => false,
        ];
        if ($job_config->driver == "sendmail") {
            $config['sendmail'] = '/usr/sbin/sendmail -bs';
        }
        Config::set('mail', $config);

        if (!empty($job_config->subject_prefix)) {
            $this->details['subject'] = "[" . $job_config->subject_prefix . "] " . $this->details['subject'];
        }
        try {
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
        } catch (Exception $email_not_sent) {
            $email->status = 'failed';
            $email->data = json_encode([
                'message' => $email_not_sent->getMessage()
            ]);
            $email->save();
            $this->fail($email_not_sent);
            throw $email_not_sent;
        }
        $fails = Mail::failures();
        if (count($fails) > 0) {
            $email->status = 'failed';
            $email->data = json_encode([
                'failures' => $fails
            ]);
            $email->save();
            $fail_exeption = new Exception("Some recipients couldn't receive the message");
            $this->fail($fail_exeption);
            throw $fail_exeption;
        }
        $email->status = 'sent';
        $email->data = json_encode([]);
        $email->save();
        return [
            'status' => $email->status,
            'data' => $email->data
        ];
    }
}
