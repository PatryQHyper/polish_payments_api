<?php

/**
 * Created with love by: Patryk Vizauer (wizjoner.dev)
 * Date: 21.03.2023 13:56
 */

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