<?php

namespace App\Components\Sms\Drivers;

use App\Components\Sms\Contracts\Driver;
use Carbon\Carbon;
use SoapClient;
use SoapFault;

class EclipSoftDriver implements Driver
{
    private $config;

    /** @var array */
    private $sentMessages = [];

    /**
     * @var SoapClient
     */
    private $client;

    /**
     * EclipSoft constructor.
     */
    public function __construct($config)
    {
        $this->config = $config;
    }

    /**
     * Send a new message to its recipients
     *
     * @param $recipients
     * @param $message
     * @param array $failedRecipients
     * @throws SoapFault
     */
    public function send($recipients, $message, &$failedRecipients = [])
    {
        $payload = [
            "emServicio" => $this->config['service'],
            "emEmisor" => $this->config['emitter'],
            "emLogin" => $this->config['login'],
            "emPwd" => $this->config['pwd'],
            "emReferencia" => $this->config['reference'],
            "emNombrePC" => $this->config['pc_name'],
            "emFechaEnv" => Carbon::now()->format('m/d/Y'),
            "emHoraEnv" => Carbon::now()->format('H:i'),
            "emLimite" => '1',
            "emTotalMes" => '1',
        ];
        $payload['emKey'] = md5(
            "{$payload['emServicio']};csms@auto;{$payload['emEmisor']};{$payload['emLogin']};{$payload['emPwd']};{$payload['emReferencia']}"
        );

        if (!array($recipients)) {
            $recipients[] = $recipients;
        }

        $clientOptions = array(
            'trace' => TRUE,
            'exceptions' => TRUE,
            'cache_wsdl' => WSDL_CACHE_BOTH
        );
        try {
            $this->client = new SoapClient($this->config["endpoint"], $clientOptions);
        } catch (SoapFault $e) {
            throw $e;
        }

        foreach ($recipients as $to) {
            $request = array(
                "parEmisor" => $payload,
                "parDestinatarios" => $to,
                "parMensaje" => $message
            );
            $response = $this->client->EnviarSMS($request);
            if (($response->EnviarSMSResult->reNumErrores ?? 0) > 0) {
                $failedRecipients[] = [
                    'to' => $to,
                    'detail' => $response->EnviarSMSResult->reErrores->SErrores->reError,
                ];
            } else {
                $this->sentMessages[] = [
                    'to' => $to,
                ];
            }

        }
    }


}
