<?php

/**
 * Created with love by: Patryk Vizauer (wizjoner.dev)
 * Date: 21.03.2023 13:58
 */

namespace PatryQHyper\Payments\Providers;

use PatryQHyper\Payments\Exceptions\GeneratePaymentException;
use PatryQHyper\Payments\Providers\Miscellaneous\CashBill\AbstractEnvironment;
use PatryQHyper\Payments\Providers\Miscellaneous\CashBill\ProductionEnvironment;
use PatryQHyper\Payments\Providers\Setters\CashBillSetters;
use PatryQHyper\Payments\Responses\PaymentGeneratedResponse;

class CashBill extends CashBillSetters
{
    public function __construct(
        private string              $shopId,
        private string              $shopKey,
        private AbstractEnvironment $environment = new ProductionEnvironment(),
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

    public function handleNotification()
    {
        // TODO: Implement handleNotification() method.
    }
}