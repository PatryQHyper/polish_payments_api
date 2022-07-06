<?php

/**
 * Created with love by: Patryk Vizauer (patryqhyper.pl)
 * Date: 16.05.2022 20:08
 * Using: PhpStorm
 */

namespace PatryQHyper\Payments\Online;

class PaymentGeneratedResponse
{
    private ?string $transactionId = null;
    private ?string $transactionUrl = null;

    public function __construct(?string $transactionUrl = null, ?string $transactionId = null)
    {
        $this->transactionUrl = $transactionUrl;
        $this->transactionId = $transactionId;
    }

    public function getTransactionId(): ?string
    {
        return $this->transactionId;
    }

    public function getTransactionUrl(): ?string
    {
        return $this->transactionUrl;
    }
}