<?php

namespace App\Jobs;

use App\Models\PushDevice;
use App\Models\PushLog;
use App\Models\PushSetting;
use Exception;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Support\Facades\Config;

class SendPushJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $details;
    public $tries = 1;
    public $queue = 'push';

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
     * @return array
     * @throws GuzzleException
     */
    public function handle()
    {
        /**
         * GetConfigs Based on the details
         */
        $push = PushLog::where('uuid', $this->details['uuid'])->first();

        $job_config = PushSetting::whereAppId($this->details['app_id'])->first();
        if (!$job_config) {
            $no_config_set = new Exception(
                "The app doesn't have any settings configured to send Push Notifications"
            );
            $this->fail($no_config_set);

            $push->status = 'failed';
            $push->data = json_encode([
                'message' => $no_config_set->getMessage()
            ]);
            $push->save();

            throw $no_config_set;
        }
        $config = [
            'endpoint' => $job_config->endpoint,
            'api_key' => $job_config->api_key,
        ];
        foreach ($this->details['to'] as $to) {
            $device = PushDevice::whereUuid($to)->whereAppId($this->details['app_id'])->first();
            if ($device) {
                $registration_ids[] = $device->regid;
            }
        }

        if (count($this->details['to']) == 1) {
            $payload = [
                "to" => $registration_ids[0],
                "data" => $this->details['data'],
                "notification" => $this->details['notification']
            ];
        } else {
            $payload = [
                "registration_ids" => $registration_ids,
                "data" => $this->details['data'],
                "notification" => $this->details['notification']
            ];
            $payload['registration_ids'] = array_unique($payload['registration_ids']);
        }
        try {
            $client = new Client();
            $response = $client->request('POST', $config['endpoint'], [
                'headers' => [
                    'Content-Type' => 'application/json',
                    'Authorization' => 'key=' . $config['api_key']
                ],
                'body' => json_encode($payload),
            ]);
        } catch (GuzzleException $push_not_sent) {
            $push->status = 'failed';
            $push->data = json_encode([
                'message' => $push_not_sent->getMessage()
            ]);
            $push->save();
            $this->fail($push_not_sent);
            throw $push_not_sent;
        }
        if ($response->getStatusCode() == 200) {
            $push->status = 'sent';
        } else {
            $push->status = 'failed';
        }
        $push->data = json_encode([
            'sent' => $payload,
            'fcm' => json_decode($response->getBody()->getContents())
        ]);
        $push->save();
        return [
            'status' => $push->status,
            'data' => $push->data
        ];
    }
}