<?php

namespace PatryQHyper\Payments\Online\Methods;

use PatryQHyper\Payments\Exceptions\PaymentException;
use PatryQHyper\Payments\Online\PaymentAbstract;
use PatryQHyper\Payments\Online\PaymentGeneratedResponse;

class CashBillPayment extends PaymentAbstract
{
    private string $shopId;
    private string $shopKey;
    private bool $testEnvironment;

    public const ENVIRONMENT_PRODUCTION = false;
    public const ENVIRONMENT_TEST = true;

    private float $amount;
    private string $title;
    private string $additionalData;
    private string $description;
    private string $returnUrl;
    private string $negativeReturnUrl;
    private string $email;
    private string $paymentChannel;
    private string $firstname;
    private string $surname;
    private string $language = 'pl';
    private string $currency = 'PLN';
    private string $referer;

    public function __construct(string $shopId, string $shopKey, bool $environment = self::ENVIRONMENT_PRODUCTION)
    {
        $this->shopId = $shopId;
        $this->shopKey = $shopKey;
        $this->testEnvironment = $environment;
    }

    public function setAmount(float $amount): CashBillPayment
    {
        $this->amount = $amount;
        return $this;
    }

    public function setTitle(string $title): CashBillPayment
    {
        $this->title = $title;
        return $this;
    }

    public function setAdditionalData(string $additionalData): CashBillPayment
    {
        $this->additionalData = $additionalData;
        return $this;
    }

    public function setDescription(string $description): CashBillPayment
    {
        $this->description = $description;
        return $this;
    }

    public function setReturnUrl(string $returnUrl): CashBillPayment
    {
        $this->returnUrl = $returnUrl;
        return $this;
    }

    public function setNegativeReturnUrl(string $negativeReturnUrl): CashBillPayment
    {
        $this->negativeReturnUrl = $negativeReturnUrl;
        return $this;
    }

    public function setEmail(string $email): CashBillPayment
    {
        $this->email = $email;
        return $this;
    }

    public function setPaymentChannel(string $paymentChannel): CashBillPayment
    {
        $this->paymentChannel = $paymentChannel;
        return $this;
    }

    public function setFirstname(string $firstname): CashBillPayment
    {
        $this->firstname = $firstname;
        return $this;
    }

    public function setSurname(string $surname): CashBillPayment
    {
        $this->surname = $surname;
        return $this;
    }

    public function setLanguage(string $language): CashBillPayment
    {
        $this->language = $language;
        return $this;
    }

    public function setCurrency(string $currency): CashBillPayment
    {
        $this->currency = $currency;
        return $this;
    }

    public function setReferer(string $referer): CashBillPayment
    {
        $this->referer = $referer;
        return $this;
    }

    /**
     * @throws PaymentException
     */
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

        $parameters['sign'] = sha1(implode($parameters) . $this->shopKey);

        $request = $this->doRequest(sprintf('https://pay.cashbill.pl/%s/rest/payment/%s', $this->testEnvironment ? 'testws' : 'ws', $this->shopId), [
            'form_params' => $parameters,
            'headers' => [
                'Content-Type' => 'application/x-www-form-urlencoded; charset=UTF8'
            ]
        ], 'POST', false, false);

        if ($request->getStatusCode() !== 200) {
            throw new PaymentException('CashBill error: ' . $request->getBody());
        }

        $json = json_decode($request->getBody());
        if (isset($json->id) && isset($json->redirectUrl)) {
            return new PaymentGeneratedResponse($json->redirectUrl, $json->id);
        }

        throw new PaymentException('unexpected error');
    }

    /**
     * @throws PaymentException
     */
    public function getTransactionInfo(string $transactionId)
    {
        $request = $this->doRequest(sprintf('https://pay.cashbill.pl/%s/rest/payment/%s/%s?sign=%s', $this->testEnvironment ? 'testws' : 'ws', $this->shopId, $transactionId, sha1($transactionId . $this->shopKey)), [], 'GET', false, false);

        if ($request->getStatusCode() !== 200) {
            throw new PaymentException('CashBill error: ' . $request->getBody());
        }

        return json_decode($request->getBody());
    }

    /**
     * @throws PaymentException
     */
    public function setRedirectUrls(string $transactionId): bool
    {
        if (!isset($this->returnUrl) || !isset($this->negativeReturnUrl)) {
            throw new PaymentException('returnUrl and negativeReturnUrl are required. Set them via setters.');
        }

        $request = $this->doRequest(sprintf('https://pay.cashbill.pl/%s/rest/payment/%s/%s', $this->testEnvironment ? 'testws' : 'ws', $this->shopId, $transactionId), [
            'form_params' => [
                'returnUrl' => $this->returnUrl,
                'negativeReturnUrl' => $this->negativeReturnUrl,
                'sign' => sha1($transactionId . $this->returnUrl . $this->negativeReturnUrl . $this->shopKey)
            ],
            'headers' => [
                'Content-Type' => 'application/x-www-form-urlencoded; charset=UTF8'
            ]
        ], 'PUT', false, false);

        if ($request->getStatusCode() !== 204) {
            throw new PaymentException('CashBill error: ' . $request->getBody());
        }

        return true;
    }
}