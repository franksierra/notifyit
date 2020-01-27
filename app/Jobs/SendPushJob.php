<?php

namespace App\Jobs;

use App\Components\Push\Facades\Push;
use App\Models\Credential;
use App\Models\Notification\NotificationLog;
use App\Models\PushDevice;
use App\Models\PushNotificationLog;
use Exception;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Config;

class SendPushJob implements ShouldQueue
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
        self::onQueue('push');
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
        if (Config::has('push')) {
            Config::set('push', []);
        }
        $this->notificationLog = PushNotificationLog::whereJobId($this->jobData['uuid'])->first();
        /** @var Credential $credential */
        $credential = $this->notificationLog->credential()->first();
        $setting = $credential->pushSetting()->first();

        if (!$setting) {
            $no_config_set = new Exception("The app doesn't have any settings configured to send Push Notifications");
            return $this->fail($no_config_set);
        }
        if ($credential->prefix && config('app.env') != 'testing') {
            $this->jobData["payload"]->notification->title =
                "[" . $credential->prefix_value . "] " . $this->jobData["payload"]->notification->title;
        }

        $config = $setting->config;
        $config['driver'] = $setting->driver;
        Config::set('push', $config);

        $registration_ids = [];
        $regidToUid = [];
        foreach ($this->jobData['to'] as $to) {
            if ($device = PushDevice::whereUuid($to)->whereCredentialId($this->jobData['credential_id'])->first()) {
                $registration_ids[] = $device->regid;
                $regidToUid[$device->regid] = $to;
            }
        }
        $registration_ids = array_values(array_unique($registration_ids));

        try {
            Push::send($registration_ids, $this->jobData['payload']);
        } catch (Exception $push_not_sent) {
            return $this->fail($push_not_sent);
        }
        $this->notificationLog->status = 'sent';
        $pushFails = Push::failures();
        if (count($pushFails) > 0) {
            $fails = [];
            foreach ($pushFails as $pushFail) {
                $fails[] = [
                    'to' => $regidToUid[$pushFail['to']],
                    'detail' => $pushFail['detail']
                ];
            }

            $push_not_sent = new Exception("Some recipients couldn't receive the message");
            $this->notificationLog->additional = ['fails' => $fails];
            return $this->fail($push_not_sent);
        }
        $this->notificationLog->save();
    }
}
