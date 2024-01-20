<?php

namespace PatryQHyper\Payments\Responses;

class PaymentGeneratedResponse
{
    public function __construct(
        public readonly ?string                      $url = null,
        public readonly ?string                      $id = null,
        public readonly PaymentGeneratedResponseType $type = PaymentGeneratedResponseType::URL,
        public readonly ?string                      $formMethod = null,
        public readonly array                        $formParams = [],
    )
    {
    }

    public function __toString(): string
    {
        if ($this->type->isUrl()) {
            return '<a href="' . $this->url . '" id="polishpaymentsapi_a">Jeśli nie nastąpi przekierowanie, naciśnij tutaj</a><script>document.getElementById("polishpaymentsapi_a").click();</script>';
        }

        $form = '<form id="polishpaymentsapi_form" method="' . $this->formMethod . '" action="' . $this->url . '">';
        foreach ($this->formParams as $key => $value)
            $form .= '<input type="hidden" name="' . $key . '" value="' . $value . '">';
        $form .= '<button type="submit" id="polishpaymentsapi_button">Jeśli nie nastąpi przekierowanie, naciśnij tutaj</button></form><script>document.getElementById("polishpaymentsapi_form").submit();</script>';

        return $form;
    }
}