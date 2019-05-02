<?php

namespace App\Jobs;

use App\Models\SmsLog;
use App\Models\SmsSetting;
use Illuminate\Support\Facades\Config;
use Exception;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class SendSMSJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * The number of times the job may be attempted.
     *
     * @var int
     */
    public $tries = 1;
    protected $details;

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
     * @throws Exception
     */
    public function handle()
    {
        $sms = SmsLog::where('uuid', $this->details['uuid'])->first();
        /**
         * GetConfigs Based on the details
         */
        $job_config = SmsSetting::whereAppId($this->details['app_id'])->first();
        if (!$job_config) {
            $no_config_set = new Exception(
                "The app doesn't have any settings configured"
            );
            $this->fail($no_config_set);

            $sms->status = 'failed';
            $sms->data = json_encode([
                'message' => $no_config_set->getMessage()
            ]);
            $sms->save();
            throw $no_config_set;
        }
        /**
         * Las config
         */
        $config = [];
        Config::set('sms', $config);
        try {
            // Envio
            sleep(1);
        } catch (Exception $sms_not_sent) {
            $sms->status = 'failed';
            $sms->data = json_encode([
                'message' => $sms_not_sent->getMessage()
            ]);
            $sms->save();
            $this->fail($sms_not_sent);
            throw $sms_not_sent;
        }
        $sms->status = 'sent';
        $sms->data = json_encode([]);
        $sms->save();


    }
}
