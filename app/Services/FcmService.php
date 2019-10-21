<?php


namespace App\Services;


use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\Exception\RequestException;

class FcmService
{
    /**
     * @param $payload
     * @param $config
     * @return mixed|\Psr\Http\Message\ResponseInterface
     * @throws GuzzleException
     */
    public function send($payload, $config)
    {
        $client = new Client();
        $response = $client->request('POST', $config['endpoint'], [
            'headers' => [
                'Content-Type' => 'application/json',
                'Authorization' => 'key=' . $config['api_key']
            ],
            'body' => json_encode($payload),
        ]);
        return $response;
    }

}
