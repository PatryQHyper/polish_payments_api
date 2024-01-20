<?php

namespace PatryQHyper\Payments\Providers\CashBill;

use PatryQHyper\Payments\Exceptions\GeneratePaymentException;
use PatryQHyper\Payments\Exceptions\PolishPaymentsApiException;
use PatryQHyper\Payments\Providers\Notifications\CashBillNotification;
use PatryQHyper\Payments\Providers\Notifications\Notification;
use PatryQHyper\Payments\Responses\CashBillTransactionDetails;
use PatryQHyper\Payments\Responses\PaymentGeneratedResponse;

class CashBillProvider extends Setters
{
    public function __construct(
        private readonly string      $shopId,
        private readonly string      $shopKey,
        private readonly Environment $environment = Environment::PRODUCTION,
    )
    {
    }

    public function generatePayment(): PaymentGeneratedResponse
    {
        $parameters['title'] = $this->title;
        $parameters['amount.value'] = sprintf('%.2f', $this->amount);
        if (isset($this->currency)) {
            $parameters['amount.currencyCode'] = $this->currency;
        }
        if (isset($this->returnUrl)) {
            $parameters['returnUrl'] = $this->returnUrl;
        }
        if (isset($this->description)) {
            $parameters['description'] = $this->description;
        }
        if (isset($this->negativeReturnUrl)) {
            $parameters['negativeReturnUrl'] = $this->negativeReturnUrl;
        }
        if (isset($this->additionalData)) {
            $parameters['additionalData'] = $this->additionalData;
        }
        if (isset($this->paymentChannel)) {
            $parameters['paymentChannel'] = $this->paymentChannel;
        }
        if (isset($this->language)) {
            $parameters['languageCode'] = $this->language;
        }
        if (isset($this->referer)) {
            $parameters['referer'] = $this->referer;
        }
        if (isset($this->firstname)) {
            $parameters['personalData.firstName'] = $this->firstname;
        }
        if (isset($this->surname)) {
            $parameters['personalData.surname'] = $this->surname;
        }
        if (isset($this->email)) {
            $parameters['personalData.email'] = $this->email;
        }

        $parameters['sign'] = sha1(implode('', $parameters) . $this->shopKey);

        $request = $this->doRequest(sprintf('%s/payment/%s', $this->environment->getUrl(), $this->shopId), [
            'form_params' => $parameters,
            'headers' => [
                'Content-Type' => 'application/x-www-form-urlencoded; charset=UTF8'
            ]
        ], 'POST');

        if ($request->getStatusCode() != 200) {
            throw new GeneratePaymentException(sprintf('CashBill error: %s', $request->getBody()));
        }

        $json = json_decode($request->getBody());
        if (isset($json->id) && isset($json->redirectUrl)) {
            return new PaymentGeneratedResponse($json->redirectUrl, $json->id);
        }

        throw new GeneratePaymentException(sprintf('unexpected error (id or redirectUrl dont exist): %s', $request->getBody()));
    }

    public function validateIPN(object|array $payload): Notification
    {
        return (new CashBillNotification($payload, [
            'shopId' => $this->shopId,
            'shopKey' => $this->shopKey,
        ]))->handle();
    }

    /**
     * @throws PolishPaymentsApiException
     */
    public function getTransactionInfo(string $transactionId): object
    {
        $request = $this->doRequest(sprintf('%s/payment/%s/%s', $this->environment->getUrl(), $this->shopId, $transactionId), [
            'query' => [
                'sign' => sha1($transactionId . $this->shopKey),
            ],
        ]);

        if ($request->getStatusCode() != 200) {
            throw new PolishPaymentsApiException(sprintf('CashBill error: %s', $request->getBody()));
        }

        return new CashBillTransactionDetails(json_decode($request->getBody(), true));
    }
}