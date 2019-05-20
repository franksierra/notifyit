<?php

namespace App\Jobs;

use App\Models\SmsLog;
use App\Models\SmsSetting;
use Carbon\Carbon;
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

    /**
     * Create a new job instance.
     *
     * @param $details
     */
    public function __construct($details)
    {
        $this->details = $details;
        self::onQueue('sms');
    }

    /**
     * Execute the job.
     *
     * @return array
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
        $enviarSMS = [];
        try {
            $now = Carbon::now();
            $payload = json_decode($job_config->payload);
            $clsEmisor = [
                'emServicio' => $payload->emServicio,
                'emEmisor' => $payload->emEmisor,
                'emLogin' => $payload->emLogin,
                'emPwd' => $payload->emPwd,
                'emReferencia' => $sms->id,
                'emFechaEnv' => $now->format('m/d/Y'),
                'emHoraEnv' => $now->format('H:i'),
                'emNombrePC' => $payload->emNombrePC,
                'emKey' => '',
                'emLimite' => $payload->emLimite,
                'emTotalMes' => $payload->emTotalMes
            ];
            $servicio = $clsEmisor['emServicio'];
            $emisor = $clsEmisor['emEmisor'];
            $login = $clsEmisor['emLogin'];
            $pwd = $clsEmisor['emPwd'];
            $referencia = $clsEmisor['emReferencia'];
            $clsEmisor['emKey'] = md5("$servicio;csms@auto;$emisor;$login;$pwd;$referencia");
            $enviarSMS = [
                'parEmisor' => $clsEmisor,
                'parDestinatarios' => $this->details["to"][0],
                'parMensaje' => $this->details["text"]
            ];

            $options = [
                'trace' => true,
                'cache_wsdl' => WSDL_CACHE_NONE
            ];
            $client = new SoapClient($config['endpoint'], $options); // null for non-wsdl mode
            $response = $client->EnviarSMS($enviarSMS);
        } catch (Exception $sms_not_sent) {
            $sms->status = 'failed';
            $sms->data = json_encode([
                'message' => $sms_not_sent->getMessage()
            ]);
            $sms->save();
            $this->fail($sms_not_sent);
            throw $sms_not_sent;
        }
        if ($response->EnviarSMSResult->reNumErrores == 0) {
            $sms->status = 'sent';
        } else {
            $sms->status = 'failed';
        }
        $sms->data = json_encode([
            'sent' => $enviarSMS,
            'fcm' => json_decode($response)
        ]);
        $sms->save();

        return [
            'status' => $sms->status,
            'data' => $sms->data
        ];
    }
}
