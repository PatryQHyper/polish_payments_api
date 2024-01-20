<?php

namespace PatryQHyper\Payments\Responses;

enum PaymentGeneratedResponseType
{
    case FORM;
    case URL;

    public function isUrl(): bool
    {
        return $this === self::URL;
    }

    public function isForm(): bool
    {
        return $this === self::FORM;
    }
}
