<?php

namespace PatryQHyper\Payments\Providers\CashBill;
use PatryQHyper\Payments\Providers\Provider;

abstract class Setters extends Provider
{
    protected float $amount;
    protected string $title;
    protected string $additionalData;
    protected string $description;
    protected string $returnUrl;
    protected string $negativeReturnUrl;
    protected string $email;
    protected string $paymentChannel;
    protected string $firstname;
    protected string $surname;
    protected string $language = 'pl';
    protected string $currency = 'PLN';
    protected string $referer;

    public function setAmount(float $amount): Setters
    {
        $this->amount = $amount;
        return $this;
    }

    public function setTitle(string $title): Setters
    {
        $this->title = $title;
        return $this;
    }

    public function setAdditionalData(string $additionalData): Setters
    {
        $this->additionalData = $additionalData;
        return $this;
    }

    public function setDescription(string $description): Setters
    {
        $this->description = $description;
        return $this;
    }

    public function setReturnUrl(string $returnUrl): Setters
    {
        $this->returnUrl = $returnUrl;
        return $this;
    }

    public function setNegativeReturnUrl(string $negativeReturnUrl): Setters
    {
        $this->negativeReturnUrl = $negativeReturnUrl;
        return $this;
    }

    public function setEmail(string $email): Setters
    {
        $this->email = $email;
        return $this;
    }

    public function setPaymentChannel(string $paymentChannel): Setters
    {
        $this->paymentChannel = $paymentChannel;
        return $this;
    }

    public function setFirstname(string $firstname): Setters
    {
        $this->firstname = $firstname;
        return $this;
    }

    public function setSurname(string $surname): Setters
    {
        $this->surname = $surname;
        return $this;
    }

    public function setLanguage(string $language): Setters
    {
        $this->language = $language;
        return $this;
    }

    public function setCurrency(string $currency): Setters
    {
        $this->currency = $currency;
        return $this;
    }

    public function setReferer(string $referer): Setters
    {
        $this->referer = $referer;
        return $this;
    }
}