<?php

namespace PatryQHyper\Payments\Providers\Setters;

use PatryQHyper\Payments\Providers\Miscellaneous\CashBill\AbstractEnvironment;
use PatryQHyper\Payments\Providers\Provider;

abstract class CashBillSetters extends Provider
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

    public function setAmount(float $amount): CashBillSetters
    {
        $this->amount = $amount;
        return $this;
    }

    public function setTitle(string $title): CashBillSetters
    {
        $this->title = $title;
        return $this;
    }

    public function setAdditionalData(string $additionalData): CashBillSetters
    {
        $this->additionalData = $additionalData;
        return $this;
    }

    public function setDescription(string $description): CashBillSetters
    {
        $this->description = $description;
        return $this;
    }

    public function setReturnUrl(string $returnUrl): CashBillSetters
    {
        $this->returnUrl = $returnUrl;
        return $this;
    }

    public function setNegativeReturnUrl(string $negativeReturnUrl): CashBillSetters
    {
        $this->negativeReturnUrl = $negativeReturnUrl;
        return $this;
    }

    public function setEmail(string $email): CashBillSetters
    {
        $this->email = $email;
        return $this;
    }

    public function setPaymentChannel(string $paymentChannel): CashBillSetters
    {
        $this->paymentChannel = $paymentChannel;
        return $this;
    }

    public function setFirstname(string $firstname): CashBillSetters
    {
        $this->firstname = $firstname;
        return $this;
    }

    public function setSurname(string $surname): CashBillSetters
    {
        $this->surname = $surname;
        return $this;
    }

    public function setLanguage(string $language): CashBillSetters
    {
        $this->language = $language;
        return $this;
    }

    public function setCurrency(string $currency): CashBillSetters
    {
        $this->currency = $currency;
        return $this;
    }

    public function setReferer(string $referer): CashBillSetters
    {
        $this->referer = $referer;
        return $this;
    }
}