<?php

namespace PatryQHyper\Payments;

use GuzzleHttp\Client;

class WebClient
{
    protected function doRequest($url, $data = [], $method='GET', bool $use_fgc_to_get=true, bool $get_body=true)
    {
        $method = strtoupper($method);
        if($method == 'GET' && $use_fgc_to_get) return $this->doGetRequest($url, $data);

        $client = new Client();
        try {
            $data['headers']['User-Agent'] = 'PatryQHyperPaymentsWrapper/2.0.0';
            $response = $client->request($method, $url, $data);
            if($get_body) return json_decode($response->getBody());
        }
        catch (\Exception $e)
        {
            return $e->getResponse();
        }

        return $response;
    }

    private function doGetRequest($url, $data)
    {
        return json_decode(@file_get_contents($url.'?'.http_build_query($data)));
    }
}