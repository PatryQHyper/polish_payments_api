<?php

require 'base.php';

echo new \PatryQHyper\Payments\Responses\PaymentGeneratedResponse(
    'https://example.com',
    null,
    \PatryQHyper\Payments\Responses\PaymentGeneratedResponseType::FORM,
    formMethod: 'POST', formParams: ['foo' => 'bar', 'baz' => 'foo'],
);