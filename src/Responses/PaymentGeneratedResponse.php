<?php

namespace PatryQHyper\Payments\Responses;

class PaymentGeneratedResponse
{
    public function __construct(
        private ?string $redirectUrl = null,
        private ?string $id = null,
    )
    {
    }

    public function getRedirectUrl(): ?string
    {
        return $this->redirectUrl;
    }

    public function getId(): ?string
    {
        return $this->id;
    }
}