<?php

/**
 * Created with love by: Patryk Vizauer (patryqhyper.pl)
 * Date: 05.06.2022 23:16
 * Using: PhpStorm
 */

namespace PatryQHyper\Payments\Sms\Providers;

use PatryQHyper\Payments\Exceptions\InvalidSmsCodeException;
use PatryQHyper\Payments\Exceptions\UsedSmsCodeException;
use PatryQHyper\Payments\Sms\SmsAbstract;

class CashBillSms extends SmsAbstract
{
    private string $token;
    private int $smsNumber = 0;

    public function __construct(string $token)
    {
        $this->token = $token;
    }

    public function check(string $code)
    {
        $request = $this->doRequest(sprintf('https://sms.cashbill.pl/code/%s/%s', $this->token, $code), [], 'GET', false, false);

        $json = json_decode($request);
        if (isset($json->error))
            throw new InvalidSmsCodeException();

        if (!$json->active || $json->activeFrom != null)
            throw new UsedSmsCodeException();


        $this->smsNumber = $json->number;
        return true;
    }

    public function getSmsNumber(): int
    {
        return $this->smsNumber;
    }
}