<?php

namespace PatryQHyper\Payments\Online\Methods;

use PatryQHyper\Payments\Exceptions\PaymentException;
use PatryQHyper\Payments\Online\PaymentAbstract;
use PatryQHyper\Payments\Online\PaymentGeneratedResponse;

class HotPayMobilePayment extends PaymentAbstract
{
    private string $secret;
    private string $method;

    public const METHOD_DIRECTBILLING = 'directbilling';
    public const METHOD_PREMIUMRATE = 'premiumrate';

    public function __construct(string $secret, string $method)
    {
        if (!in_array($method, [self::METHOD_DIRECTBILLING, self::METHOD_PREMIUMRATE])) {
            throw new PaymentException('invalid method');
        }

        $this->secret = $secret;
        $this->method = $method;
    }

    private float $amount;
    private string $description;
    private string $redirectSuccessUrl;
    private string $redirectFailUrl;
    private string $orderId;

    public function setAmount(float $amount): self
    {
        $this->amount = $amount;
        return $this;
    }

    public function setDescription(string $description): self
    {
        $this->description = $description;
        return $this;
    }

    public function setRedirectSuccessUrl(string $redirectSuccessUrl): self
    {
        $this->redirectSuccessUrl = $redirectSuccessUrl;
        return $this;
    }

    public function setRedirectFailUrl(string $redirectFailUrl): self
    {
        $this->redirectFailUrl = $redirectFailUrl;
        return $this;
    }

    public function setOrderId(string $orderId): self
    {
        $this->orderId = $orderId;
        return $this;
    }

    public function generatePayment(): PaymentGeneratedResponse
    {
        return new PaymentGeneratedResponse(sprintf('https://%s.hotpay.pl/?%s', $this->method, http_build_query([
            'SEKRET' => $this->secret,
            'KWOTA' => $this->amount,
            'NAZWA_USLUGI' => $this->description,
            'PRZEKIEROWANIE_SUKCESS' => $this->redirectSuccessUrl,
            'PRZEKIEROWANIE_BLAD' => $this->redirectFailUrl,
            'ID_ZAMOWIENIA' => $this->orderId
        ])));
    }
}