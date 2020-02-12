<?php

namespace App\Jobs;

use App\Components\Sms\Facades\Sms;
use App\Models\Credential;
use App\Models\Notification\NotificationLog;
use App\Models\SmsNotificationLog;
use Exception;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Config;

class SendSmsJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $jobData;
    /** @var NotificationLog */
    protected $notificationLog;
    public $tries = 1;

    /**
     * Create a new job instance.
     *
     * @param $details
     */
    public function __construct($details)
    {
        self::onQueue('sms');
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
     */
    public function handle()
    {
        if (Config::has('sms')) {
            Config::set('sms', []);
        }
        $this->notificationLog = SmsNotificationLog::whereJobId($this->jobData['uuid'])->first();
        /** @var Credential $credential */
        $credential = $this->notificationLog->credential()->first();
        $setting = $credential->smsSetting()->first();

        if (!$setting) {
            $no_config_set = new Exception("The app doesn't have any settings configured to send Sms");
            return $this->fail($no_config_set);
        }
        if ($credential->prefix && config('app.env') != 'testing') {
            $this->jobData['text'] = "[" . $credential->prefix_value . "] " . $this->jobData['text'];
        }

        $config = $setting->config;
        $config['driver'] = $setting->driver;

        Config::set('sms', $config);

        try {
            Sms::send($this->jobData['to'], $this->jobData['text']);
        } catch (Exception $sms_not_sent) {
            return $this->fail($sms_not_sent);
        }

        $smsFails = Sms::failures();
        if (count($smsFails) > 0) {
            $sms_not_sent = new Exception("Some recipients couldn't receive the message");
            $this->notificationLog->additional = ['fails' => $smsFails];
            return $this->fail($sms_not_sent);
        }

        $this->notificationLog->status = 'sent';
        $this->notificationLog->save();
    }
}
