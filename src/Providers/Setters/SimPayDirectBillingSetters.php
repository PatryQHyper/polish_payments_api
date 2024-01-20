<?php

namespace PatryQHyper\Payments\Providers\Setters;

use PatryQHyper\Payments\Providers\CashBillProvider;

abstract class SimPayDirectBillingSetters extends CashBillProvider
{
    protected float $amount;
    protected string $amountType = 'gross';
    protected string $description;
    protected string $control;
    protected string $returnSuccess;
    protected string $returnFail;
    protected string $phoneNumber;
    protected string $steamId;

    public function setAmount(float $amount): SimPayDirectBillingSetters
    {
        $this->amount = $amount;
        return $this;
    }

    public function setAmountType(string $amountType): SimPayDirectBillingSetters
    {
        $this->amountType = $amountType;
        return $this;
    }

    public function setDescription(string $description): SimPayDirectBillingSetters
    {
        $this->description = $description;
        return $this;
    }

    public function setControl(string $control): SimPayDirectBillingSetters
    {
        $this->control = $control;
        return $this;
    }

    public function setReturnSuccess(string $returnSuccess): SimPayDirectBillingSetters
    {
        $this->returnSuccess = $returnSuccess;
        return $this;
    }

    public function setReturnFail(string $returnFail): SimPayDirectBillingSetters
    {
        $this->returnFail = $returnFail;
        return $this;
    }

    public function setPhoneNumber(string $phoneNumber): SimPayDirectBillingSetters
    {
        $this->phoneNumber = $phoneNumber;
        return $this;
    }

    public function setSteamId(string $steamId): SimPayDirectBillingSetters
    {
        $this->steamId = $steamId;
        return $this;
    }
}