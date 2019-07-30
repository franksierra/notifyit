<?php

namespace App\Jobs;

use App\Models\SmsLog;
use App\Models\SmsSetting;
use Carbon\Carbon;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Support\Facades\Config;
use Exception;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use SoapClient;

class SendSMSJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $details;
    public $tries = 1;
    public $uuid = null;

    /**
     * Create a new job instance.
     *
     * @param $details
     */
    public function __construct($details)
    {
        $this->details = $details;
        $this->uuid = $this->details['uuid'];
        self::onQueue('sms');
    }

    /**
     * Execute the job.
     *
     * @return array
     * @throws GuzzleException
     */
    public function handle()
    {
        $sms = SmsLog::where('uuid', $this->uuid)->first();
        /**
         * GetConfigs Based on the details
         */
        $job_config = SmsSetting::whereAppId($this->details['app_id'])->first();
        if (!$job_config) {
            $no_config_set = new Exception(
                "The app doesn't have any settings configured to send SMS"
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
        $config = [
            'endpoint' => $job_config->endpoint,
        ];
        Config::set('sms', $config);
        $response = null;
        $status_array = [];
        $sms->status = 'sent';
        foreach ($this->details["to"] as $to) {
            $payload = [
                'to' => $to,
                'text' => $this->details["text"]
            ];
            try {
                $client = new Client();
                $response = $client->request('POST', $config['endpoint'], [
                    'headers' => [
                        'Content-Type' => 'application/json',
                    ],
                    'body' => json_encode($payload),
                ]);
            } catch (GuzzleException $sms_not_sent) {
                $sms->status = 'failed';
                $sms->data = json_encode([
                    'message' => $sms_not_sent->getMessage()
                ]);
                $sms->save();
                $this->fail($sms_not_sent);
                throw $sms_not_sent;
            }
            if ($response->getStatusCode() == 200) {
                $status_array[$to] = 'sent';
            } else {
                $status_array[$to] = 'fail';
                $sms->status = 'fail';
            }
        }
        $sms->data = json_encode($status_array);
        $sms->save();

        return [
            'status' => $sms->status,
            'data' => $sms->data
        ];
    }
}
