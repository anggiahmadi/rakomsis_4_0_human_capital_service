<?php

namespace App\Traits;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\ClientException;

trait ConsumesExternalService
{
    /**
     * Send a request to any service
     * @return string
     */
    public function performRequest($method, $requestUrl, $formParams = [], $headers = [])
    {
        $client = new Client([
            'base_uri' => $this->baseUri,
        ]);

        if (isset($this->secret)) {
            $headers['Authorization'] = $this->secret;
        }

        try {
            $response = $client->request($method, $requestUrl, ['json' => $formParams, 'headers' => $headers]);
        }
        catch (ClientException $e) {
            $response = $e->getResponse();
        }

        $returnResponse['data'] = json_decode($response->getBody()->getContents());

        $returnResponse['status_code'] = $response->getStatusCode();

        return $returnResponse;
    }
}
