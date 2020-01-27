<?php

namespace App\Components\Push\Drivers;

use App\Components\Push\Contracts\Driver;
use GuzzleHttp\Client;
use Psr\Http\Message\ResponseInterface;

class Fcm implements Driver
{
    private $config;

    /** @var Client $client */
    private $client;

    /** @var array */
    private $sentNotifications = [];

    /**
     * FireBaseCloudMessaging constructor.
     * @param $config
     */
    public function __construct($config)
    {
        $this->client = new Client();
        $this->config = $config;
    }

    /**
     * @param array|string $recipients
     * @param string $payload
     * @param array $failedRecipients
     * @return ResponseInterface|void
     *
     */
    public function send($recipients, $payload, &$failedRecipients = [])
    {
        $recipientsChunked = array_chunk($recipients, 1000); // 1000 max regids allowed by Google
        foreach ($recipientsChunked as $ids) {
            $payload->registration_ids = $ids;
            $response = $this->request($payload);
            if ($response->getStatusCode() == 200) {
                $responseString = $response->getBody()->getContents();
                $fcm = json_decode($responseString);
                for ($i = 0; $i < count($fcm->results); $i++) {
                    if (isset($fcm->results[$i]->error)) {
                        $failedRecipients[] = [
                            'to' => $ids[$i],
                            'detail' => $fcm->results[$i]->error
                        ];
                    } else {
                        $this->sentNotifications[] = [
                            'to' => $ids[$i]
                        ];
                    }
                }
            }
        }
    }

    /**
     * @param $payload
     * @return mixed|ResponseInterface
     *
     */
    private function request($payload)
    {
        return $this->client->request('POST', $this->config['endpoint'], [
            'headers' => [
                'Content-Type' => 'application/json',
                'Authorization' => 'key=' . $this->config['api_key']
            ],
            'body' => json_encode($payload),
        ]);
    }

}
