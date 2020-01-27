<?php

namespace App\Jobs;

use App\Mail\DynamicEmail;
use App\Models\Credential;
use App\Models\EmailNotificationLog;
use Exception;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;

class SendEmailJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $jobData;
    /** @var EmailNotificationLog */
    protected $notificationLog;
    public $tries = 1;

    /**
     * Create a new job instance.
     *
     * @param $details
     */
    public function __construct($details)
    {
        self::onQueue('emails');
        $this->jobData = $details;
    }

    /**
     * @param Exception $exception
     */
    public function fail(Exception $exception = null)
    {
        $this->notificationLog->status = 'failed';
        $this->notificationLog->exception = [
            'message' => $exception->getMessage(),
            'exception' => $exception
        ];
        $this->notificationLog->save();

        if ($this->job) {
            $this->job->fail($exception);
        }
    }

    /**
     * Execute the job.
     *
     * @return void
     * @throws Exception
     */
    public function handle()
    {
        if (Config::has('mail')) {
            Config::set('mail', []);
        }
        $this->notificationLog = EmailNotificationLog::whereJobId($this->jobData['uuid'])->first();
        /** @var Credential $credential */
        $credential = $this->notificationLog->credential()->first();
        $setting = $credential->emailSetting()->first();

        if (!$setting) {
            $no_config_set = new Exception("The app doesn't have any settings configured to send Emails");
            return $this->fail($no_config_set);
        }

        if ($credential->prefix && config('app.env') != 'testing') {
            $this->jobData['subject'] = "[" . $credential->prefix_value . "] " . $this->jobData['subject'];
        }
        $config = $setting->config;
        $config['driver'] = $setting->driver;
        $config['sendmail'] = '/usr/sbin/sendmail -bs';

        Config::set('mail', $config);
        try {
            $dynamicEmail = new DynamicEmail($this->jobData);
            $dynamicEmail
                ->to($this->jobData['to'])
                ->cc($this->jobData['cc'])
                ->bcc($this->jobData['bcc'])
                ->html($this->jobData['body']);

            foreach ($this->jobData['attachments'] as $attachment) {
                if (Storage::disk('local')->exists($attachment['file'])) {
                    $dynamicEmail->attachData(
                        Storage::disk('local')->get($attachment['file']),
                        $attachment["name"] . "." . $attachment["format"]
                    );
                }
            }
            Mail::send($dynamicEmail);
        } catch (Exception $email_not_sent) {
            return $this->fail($email_not_sent);
        }
        //TODO: Maybe this need a little refactor... See comment below.
        /*
         *  As failures would always return the full list of recipients, it would be a good idea to send the emails
         * one by one and build a list of failures to give them to the user instead of trowing and exception...
         */
        $emailFails = Mail::failures();
        if (count($emailFails) > 0) {
            $email_not_sent = new Exception("Some recipients couldn't receive the message");
            $this->notificationLog->additional = ['fails' => $emailFails];
            return $this->fail($email_not_sent);
        }
        $this->notificationLog->status = 'sent';
        $this->notificationLog->save();
    }
}
