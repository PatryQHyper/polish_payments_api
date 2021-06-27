<?php

namespace PatryQHyper\Payments\Sms;

use PatryQHyper\Payments\Exceptions\SmsException;
use PatryQHyper\Payments\WebClient;

class SimpaySMS extends WebClient
{
    private string $api_key;
    private string $api_password;

    private bool $is_sent = false;
    private bool $is_used = false;

    public function __construct($api_key, $api_password)
    {
        $this->api_key = $api_key;
        $this->api_password = $api_password;
    }

    public function check(int $service_id, int $number, $code): bool
    {
        $response = $this->doRequest('https://simpay.pl/api/status', [
            'json' => [
                'params' => [
                    'key' => $this->api_key,
                    'secret' => $this->api_password,
                    'service_id' => $service_id,
                    'number' => $number,
                    'code' => $code,
                ],
            ]
        ], 'POST');
        $this->is_sent = true;
        if (!isset($response->params) && !isset($response->respond)) {
            throw new SmsException('Simpay returned unexpected error ' . json_encode($response->error));
        }

        if (isset($response->respond)) {
            if ($response->respond->status == 'USED') {
                $this->is_used = true;
                return false;
            }
            return true;
        }

        return false;
    }

    public function checkIfUsed(): bool
    {
        if(!$this->is_sent)
        {
            throw new SmsException('payment is not sent');
        }

        return $this->is_used;
    }
}