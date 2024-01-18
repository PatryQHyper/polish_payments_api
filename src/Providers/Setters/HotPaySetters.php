<?php

namespace PatryQHyper\Payments\Providers\Setters;

use PatryQHyper\Payments\Providers\Notifications\Notification;
use PatryQHyper\Payments\Providers\Provider;
use PatryQHyper\Payments\Responses\PaymentGeneratedResponse;

abstract class HotPaySetters extends Provider
{
    protected float $amount;
    protected string $description;
    protected string $redirectUrl;
    protected string $orderId;
    protected string $email;
    protected string $personalData;

    public function setAmount(float $amount): HotPaySetters
    {
        $this->amount = $amount;
        return $this;
    }

    public function setDescription(string $description): HotPaySetters
    {
        $this->description = $description;
        return $this;
    }

    public function setRedirectUrl(string $redirectUrl): HotPaySetters
    {
        $this->redirectUrl = $redirectUrl;
        return $this;
    }

    public function setOrderId(string $orderId): HotPaySetters
    {
        $this->orderId = $orderId;
        return $this;
    }

    public function setEmail(string $email): HotPaySetters
    {
        $this->email = $email;
        return $this;
    }

    public function setPersonalData(string $personalData): HotPaySetters
    {
        $this->personalData = $personalData;
        return $this;
    }
}