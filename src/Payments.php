<?php

namespace PatryQHyper\Payments;

use GuzzleHttp\Client;
use PatryQHyper\Payments\Online\PaymentAbstract;
use PatryQHyper\Payments\Sms\SmsAbstract;

class Payments
{
    public const version = '3.0.17';

    private ?PaymentAbstract $paymentAbstract = null;
    private ?SmsAbstract $smsAbstract = null;

    public function online(PaymentAbstract $paymentAbstract): ?PaymentAbstract
    {
        $this->paymentAbstract = $paymentAbstract;

        return $this->paymentAbstract;
    }

    public function sms(SmsAbstract $smsAbstract): ?SmsAbstract
    {
        $this->smsAbstract = $smsAbstract;

        return $this->smsAbstract;
    }
}
