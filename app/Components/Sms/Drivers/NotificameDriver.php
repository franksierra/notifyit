<?php

namespace App\Components\Sms\Drivers;

use App\Components\Sms\Contracts\Driver;
use GuzzleHttp\Client;
use SoapFault;

class NotificameDriver implements Driver
{
    private $config;

    /** @var array */
    private $sentMessages = [];

    /**
     * @var Client
     */
    private $client;

    /**
     * EclipSoft constructor.
     */
    public function __construct($config)
    {
        $this->client = new Client();
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
        foreach ($recipients as $to) {
            $url = $this->config['endpoint'] . 'http/send_to_contact?';
            $url .= 'msisdn=' . $this->config['prefix'] . $to;
            $url .= '&message=' . $message;
            if (isset($this->config['api_key'])) {
                $url .= '&api_key=' . $this->config['api_key'];
            }
            $response = $this->client->request('GET', $url);
            if ($response->getStatusCode() == 200) {
                $responseString = $response->getBody()->getContents();
                $notificame = json_decode($responseString);
                if ($notificame->sms_sent = 0) {
                    $failedRecipients[] = [
                        'to' => $to,
                        'detail' => $notificame
                    ];
                } else {
                    $this->sentMessages[] = [
                        'to' => $to
                    ];
                }
            }
        }
    }


}
