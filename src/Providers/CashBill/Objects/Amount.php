<?php

namespace PatryQHyper\Payments\Providers\CashBill\Objects;

class Amount
{
    public ?float $value;
    public ?string $currencyCode;

    public function __construct(public array $data)
    {
        $this->value = $data['value'];
        $this->currencyCode = $data['currencyCode'];
    }
}