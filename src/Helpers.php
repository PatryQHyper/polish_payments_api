<?php

/**
 * Created with love by: Patryk Vizauer (wizjoner.dev)
 * Date: 21.03.2023 13:47
 */

namespace PatryQHyper\Payments;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\Exception\RequestException;
use Psr\Http\Message\ResponseInterface;

class Helpers
{
    protected function doRequest(string $url, array $data = [], string $method = 'GET'): ResponseInterface
    {
        $client = new Client();
        $data['http_errors'] = false;
        $data['headers']['User-Agent'] = 'patryqhyper/polish_payments_api:' . PolishPaymentsApi::VERSION;

        try {
            return $client->request($method, $url, $data);
        } catch (RequestException|GuzzleException $exception) {
            return $exception->getResponse();
        }
    }
}