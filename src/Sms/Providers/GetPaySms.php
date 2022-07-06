<?php

/**
 * Created with love by: Patryk Vizauer (patryqhyper.pl)
 * Date: 08.06.2022 22:34
 * Using: PhpStorm
 */

namespace PatryQHyper\Payments\Sms\Providers;

use PatryQHyper\Payments\Exceptions\InvalidSmsCodeException;
use PatryQHyper\Payments\Exceptions\PaymentException;
use PatryQHyper\Payments\Exceptions\UsedSmsCodeException;
use PatryQHyper\Payments\Sms\SmsAbstract;

class GetPaySms extends SmsAbstract
{
    private string $apiKey;
    private string $apiSecret;

    private int $responseCode = 0;
    private array $responseCodes = [
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

    public function __construct(string $apiKey, string $apiSecret)
    {
        $this->apiKey = $apiKey;
        $this->apiSecret = $apiSecret;
    }

    public function check(string $code, int $number, bool $unlimited = false)
    {
        $request = $this->doRequest('https://getpay.pl/panel/app/common/resource/ApiResource.php', [
            'json' => [
                'apiKey' => $this->apiKey,
                'apiSecret' => $this->apiSecret,
                'number' => $number,
                'code' => $code,
                'unlimited' => $unlimited
            ]
        ], 'POST');

        $this->responseCode = $request->infoCode;

        if (!in_array($this->responseCode, [200, 400, 401]))
            throw new PaymentException(sprintf('GetPay error no. %d: %s', $this->responseCode, $this->getResponseCode()));

        if ($this->responseCode == 400)
            throw new InvalidSmsCodeException();

        if ($this->responseCode == 401)
            throw new UsedSmsCodeException();

        return true;
    }

    public function getResponseCode()
    {
        return $this->responseCodes[$this->responseCode] ?? 'undefined';
    }
}