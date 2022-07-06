<?php

/**
 * Created with love by: Patryk Vizauer (patryqhyper.pl)
 * Date: 05.06.2022 23:05
 * Using: PhpStorm
 */

namespace PatryQHyper\Payments\Sms\Providers;

use PatryQHyper\Payments\Exceptions\InvalidSmsCodeException;
use PatryQHyper\Payments\Exceptions\PaymentException;
use PatryQHyper\Payments\Exceptions\UsedSmsCodeException;
use PatryQHyper\Payments\Sms\SmsAbstract;

class HotPaySms extends SmsAbstract
{
    private string $secret;

    public function __construct(string $secret)
    {
        $this->secret = $secret;
    }

    public function check(string $code)
    {
        $request = $this->doRequest('https://apiv2.hotpay.pl/v1/sms/sprawdz', [
            'sekret' => $this->secret,
            'kod_sms' => $code
        ]);

        if ($request->status == 'ERROR' && $request->tresc != 'BLEDNA TRESC SMS')
            throw new PaymentException(sprintf('HotPay error: %s', $request->tresc));

        if ($request->status != 'SUKCESS')
            throw new InvalidSmsCodeException();

        if ($request->aktywacja != 1)
            throw new UsedSmsCodeException();

        return true;
    }
}