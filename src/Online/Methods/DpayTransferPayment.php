<?php

/**
 * Created with love by: Patryk Vizauer (patryqhyper.pl)
 * Date: 18.05.2022 22:30
 * Using: PhpStorm
 */

namespace PatryQHyper\Payments\Online\Methods;

use PatryQHyper\Payments\Exceptions\PaymentException;
use PatryQHyper\Payments\Online\PaymentAbstract;
use PatryQHyper\Payments\Online\PaymentGeneratedResponse;

class DpayTransferPayment extends PaymentAbstract
{
    private string $serviceName;
    private string $serviceHash;
    private bool $testEnvironment;

    public const ENVIRONMENT_PRODUCTION = false;
    public const ENVIRONMENT_TEST = true;

    public const STYLE_DEFAULT = 'default';
    public const STYLE_GREEN = 'default';
    public const STYLE_DARK = 'dark';
    public const STYLE_ORANGE = 'orange';

    private float $amount;
    private string $successUrl;
    private string $failUrl;
    private string $ipnUrl;
    private bool $installment;
    private bool $creditCard;
    private bool $paysafecard;
    private bool $paypal;
    private bool $noBanks;
    private string $channel;
    private string $email;
    private string $clientName;
    private string $clientSurname;
    private string $description;
    private string $custom;
    private string $style;

    public function __construct(string $serviceName, string $serviceHash, bool $testEnvironment = self::ENVIRONMENT_PRODUCTION)
    {
        $this->serviceName = $serviceName;
        $this->serviceHash = $serviceHash;
        $this->testEnvironment = $testEnvironment;
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

    public function setIpnUrl(string $ipnUrl)
    {
        $this->ipnUrl = $ipnUrl;
        return $this;
    }

    public function setInstallment(bool $installment)
    {
        $this->installment = $installment;
        return $this;
    }

    public function setCreditCard(bool $creditCard)
    {
        $this->creditCard = $creditCard;
        return $this;
    }

    public function setPaysafecard(bool $paysafecard)
    {
        $this->paysafecard = $paysafecard;
        return $this;
    }

    public function setPaypal(bool $paypal)
    {
        $this->paypal = $paypal;
        return $this;
    }

    public function setNoBanks(bool $noBanks)
    {
        $this->noBanks = $noBanks;
        return $this;
    }

    public function setChannel(string $channel)
    {
        $this->channel = $channel;
        return $this;
    }

    public function setEmail(string $email)
    {
        $this->email = $email;
        return $this;
    }

    public function setClientName(string $clientName)
    {
        $this->clientName = $clientName;
        return $this;
    }

    public function setClientSurname(string $clientSurname)
    {
        $this->clientSurname = $clientSurname;
        return $this;
    }

    public function setDescription(string $description)
    {
        $this->description = $description;
        return $this;
    }

    public function setCustom(string $custom)
    {
        $this->custom = $custom;
        return $this;
    }

    public function setStyle(string $style)
    {
        $this->style = $style;
        return $this;
    }

    public function generatePayment()
    {
        $array['service'] = $this->serviceName;
        $array['value'] = sprintf('%.2f', $this->amount);
        $array['url_success'] = $this->successUrl;
        $array['url_fail'] = $this->failUrl;
        $array['url_ipn'] = $this->ipnUrl;
        $array['accept_tos'] = 1;
        $array['checksum'] = hash('sha256', implode('|', [
            $this->serviceName,
            $this->serviceHash,
            sprintf('%.2f', $this->amount),
            $this->successUrl,
            $this->failUrl,
            $this->ipnUrl,
        ]));
        if (isset($this->installment)) $array['installment'] = $this->installment;
        if (isset($this->creditCard)) $array['creditcard'] = $this->creditCard;
        if (isset($this->paysafecard)) $array['paysafecard'] = $this->paysafecard;
        if (isset($this->paypal)) $array['paypal'] = $this->paypal;
        if (isset($this->noBanks)) $array['nobanks'] = $this->noBanks;
        if (isset($this->channel)) $array['channel'] = $this->channel;
        if (isset($this->email)) $array['email'] = $this->email;
        if (isset($this->clientName)) $array['client_name'] = $this->clientName;
        if (isset($this->clientSurname)) $array['client_surname'] = $this->clientSurname;
        if (isset($this->description)) $array['description'] = $this->description;
        if (isset($this->custom)) $array['custom'] = $this->custom;
        if (isset($this->style)) $array['style'] = $this->style;

        $request = $this->doRequest(sprintf('https://secure%s.dpay.pl/register', ($this->testEnvironment == self::ENVIRONMENT_TEST ? '-test' : '')), [
            'json' => $array,
            'headers' => [
                'Content-type' => 'application/json',
                'Accept' => 'application/json',
            ]
        ], 'POST');

        if (!$request->status || $request->error)
            throw new PaymentException('DPay error: ' . $request->msg);

        return new PaymentGeneratedResponse(
            $request->msg,
            $request->transactionId,
        );
    }

    public function getTransactionInfo(string $transactionId)
    {
        $request = $this->doRequest(sprintf('https://panel.%s.pl/api/v1/pbl/details', ($this->testEnvironment == self::ENVIRONMENT_TEST ? 'digitalpayments' : 'dpay')), [
            'json' => [
                'service' => $this->serviceName,
                'transaction_id' => $transactionId,
                'checksum' => hash('sha256', $this->serviceName . '|' . $transactionId . '|' . $this->serviceHash)
            ],
            'headers' => [
                'Content-type' => 'application/json',
                'Accept' => 'application/json',
            ]
        ], 'POST');

        if (isset($request->message))
            throw new PaymentException('Dpay error: ' . json_encode($request));

        return $request->transaction;
    }
}