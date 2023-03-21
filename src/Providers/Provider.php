<?php

/**
 * Created with love by: Patryk Vizauer (wizjoner.dev)
 * Date: 21.03.2023 13:47
 */

namespace PatryQHyper\Payments\Providers;

use PatryQHyper\Payments\Exceptions\GeneratePaymentException;
use PatryQHyper\Payments\Exceptions\PolishPaymentsApiException;
use PatryQHyper\Payments\Helpers;
use PatryQHyper\Payments\Responses\PaymentGeneratedResponse;

abstract class Provider extends Helpers
{
    /**
     * @throws GeneratePaymentException
     */
    abstract public function generatePayment(): PaymentGeneratedResponse;

    /**
     * @throws PolishPaymentsApiException
     */
    abstract public function handleNotification();
}