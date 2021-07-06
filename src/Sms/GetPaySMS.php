<?php

namespace PatryQHyper\Payments\Sms;

use PatryQHyper\Payments\Exceptions\SmsException;

use PatryQHyper\Payments\WebClient;

class GetPaySMS extends WebClient
{
    private string $api_key;
    private string $api_secret;

    private bool $is_sent = false;
    private int $response_code = 0;

    private bool $is_used = false;

    private array $codesEnum = [
        100 => 'Empty method',
        102 => 'Empty params',
        104 => 'Wrong length of client API login data (key/secret)',
        105 => 'Wrong client API login data (key/secret)',
        106 => 'Wrong client status',
        107 => 'No method require params',
        200 => 'OK',
        400 => 'SMS code not found',
        401 => 'SMS code already used',
        402 => 'System error',
    ];

    public function __construct(string $api_key, string $api_secret)
    {
        $this->api_key = $api_key;
        $this->api_secret = $api_secret;
    }

    public function check(int $number, $sms_code, bool $unlimited = false): bool
    {
        $response = $this->doRequest('https://getpay.pl/panel/app/common/resource/ApiResource.php', [
            'json' => [
                'apiKey' => $this->api_key,
                'apiSecret' => $this->api_secret,
                'number' => $number,
                'code' => $sms_code,
                'unlimited' => $unlimited,
            ]
        ], 'POST');

        $this->is_sent = true;

        $this->response_code = $response->infoCode;

        if (!in_array($this->response_code, [200, 400, 401])) {
            throw new SmsException('getpay returned error (' . $this->response_code . ') ' . $this->codesEnum[$this->response_code], $this->response_code);
        }

        if (isset($response->response) && $this->response_code == 200 && $response->response) return true;
        else if(isset($response->response) && $this->response_code == 401) $this->is_used = true;

        return false;
    }

    public function getResponseCode(): int
    {
        if (!$this->is_sent) {
            throw new SmsException('payment is not sent');
        }

        return $this->response_code;
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