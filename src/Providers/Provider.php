<?php

namespace PatryQHyper\Payments\Providers;

use PatryQHyper\Payments\Exceptions\GeneratePaymentException;
use PatryQHyper\Payments\Exceptions\NotificationException;
use PatryQHyper\Payments\Helpers;
use PatryQHyper\Payments\Providers\Notifications\Notification;
use PatryQHyper\Payments\Responses\PaymentGeneratedResponse;

abstract class Provider extends Helpers
{
    /**
     * @throws GeneratePaymentException
     */
    abstract public function generatePayment(): PaymentGeneratedResponse;

    /**
     * @throws NotificationException
     */
    abstract public function handleNotification(object|array $payload): Notification;
}