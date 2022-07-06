<?php

/**
 * Created with love by: Patryk Vizauer (patryqhyper.pl)
 * Date: 18.05.2022 23:25
 * Using: PhpStorm
 */

namespace PatryQHyper\Payments\Online\Methods;

use PatryQHyper\Payments\Exceptions\PaymentException;
use PatryQHyper\Payments\Online\PaymentAbstract;
use PatryQHyper\Payments\Online\PaymentGeneratedResponse;

class DpayDirectbillingPayment extends PaymentAbstract
{
    private string $guid;
    private string $secretKey;

    private float $amount;
    private string $successUrl;
    private string $failUrl;
    private string $custom;

    public function __construct(string $guid, string $secretKey)
    {
        $this->guid = $guid;
        $this->secretKey = $secretKey;
    }

    public function setAmount(float $amount)
    {
        $this->amount = $amount;
        return $this;
    }

    public function setSuccessUrl(string $successUrl)
    {
        $this->successUrl = $successUrl;
        return $this;
    }

    public function setFailUrl(string $failUrl)
    {
        $this->failUrl = $failUrl;
        return $this;
    }

    public function setCustom(string $custom)
    {
        $this->custom = $custom;
        return $this;
    }

    public function generatePayment()
    {
        $array['guid'] = $this->guid;
        $array['value'] = $this->amount * 100;
        $array['url_success'] = $this->successUrl;
        $array['url_fail'] = $this->failUrl;
        if (isset($this->custom)) $array['custom'] = $this->custom;
        $array['checksum'] = hash('sha256', implode('|', [
            $this->guid,
            $this->secretKey,
            sprintf('%.2f', $this->amount),
            $this->successUrl,
            $this->failUrl
        ]));

        $request = $this->doRequest('https://secure.dpay.pl/dcb/register', [
            'json' => $array
        ], 'POST');

        if (!$request->status || $request->error)
            throw new PaymentException('DPay error: ' . $request->error);

        return new PaymentGeneratedResponse(
            $request->msg,
            str_replace('https://secure.dpay.pl/pay/?method=dcb&id=', '', $request->msg)
        );
    }
}