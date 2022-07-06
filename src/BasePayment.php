<?php

/**
 * Created with love by: Patryk Vizauer (patryqhyper.pl)
 * Date: 16.05.2022 19:42
 * Using: PhpStorm
 */

namespace PatryQHyper\Payments;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\Exception\RequestException;

class BasePayment
{
    protected function doRequest(string $url, array $data = [], string $method = 'GET', bool $useFileGetContentsToGet = true, bool $getBody = true)
    {
        $method = strtoupper($method);
        if ($method == 'GET' && $useFileGetContentsToGet)
            return $this->doFileGetContentsRequest($url, $data);

        $client = new Client();
        try {
            $data['headers']['user-Agent'] = 'PatryQHyper/PolishPaymentsApi' . Payments::version;

            $response = $client->request($method, $url, $data);
            if ($getBody) return json_decode($response->getBody());

            return $response;
        } catch (RequestException|GuzzleException $exception) {
            return $exception->getResponse();
        }
    }

    private function doFileGetContentsRequest(string $url, array $data = [])
    {
        return json_decode(@file_get_contents($url . '?' . http_build_query($data)));
    }
}