<?php

namespace PatryQHyper\Payments\Providers\CashBill\Objects;

class Details
{
    public ?string $bankId;

    public function __construct(public readonly array $data)
    {
        $this->bankId = $data['bankId'] ?? null;
    }
}