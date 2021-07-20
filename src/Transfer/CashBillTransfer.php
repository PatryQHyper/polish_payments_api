<?php

namespace PatryQHyper\Payments\Transfer;

use PatryQHyper\Payments\Exceptions\TransferException;
use PatryQHyper\Payments\WebClient;

class CashBillTransfer extends WebClient
{
    private string $shop_id;
    private string $key;
    private bool $test_environment;

    private bool $payment_generated = false;
    private ?string $payment_id;
    private ?string $payment_url;

    public function __construct(string $shop_id, string $key, bool $test_environment = false)
    {
        $this->shop_id = $shop_id;
        $this->key = $key;
        $this->test_environment = $test_environment;
    }

    public function getChannels($lang = 'pl')
    {
        return $this->doRequest('https://pay.cashbill.pl/' . ($this->test_environment ? 'testws' : 'ws') . '/rest/paymentchannels/' . $this->shop_id . '/' . $lang);
    }

    public function generate(
        float $price,
        string $title,
        ?string $additionalData = NULL,
        ?string $description = NULL,
        ?string $returnUrl = NULL,
        ?string $negativeReturnUrl = NULL,
        ?string $email = NULL,
        ?string $paymentChannel = NULL,
        ?string $firstName = NULL,
        ?string $surname = NULL,
        ?string $language = NULL,
        ?string $currency = 'PLN',
        $referer = NULL
    ): bool
    {
        $params['title'] = $title;
        $params['amount.value'] = $price;
        if (!is_null($currency)) $params['amount.currencyCode'] = $currency;
        if (!is_null($returnUrl)) $params['returnUrl'] = $returnUrl;
        if (!is_null($description)) $params['description'] = $description;
        if (!is_null($negativeReturnUrl)) $params['negativeReturnUrl'] = $negativeReturnUrl;
        if (!is_null($additionalData)) $params['additionalData'] = $additionalData;
        if (!is_null($paymentChannel)) $params['paymentChannel'] = $paymentChannel;
        if (!is_null($language)) $params['languageCode'] = $language;
        if (!is_null($referer)) $params['referer'] = $referer;
        if (!is_null($firstName)) $params['personalData.firstName'] = $firstName;
        if (!is_null($surname)) $params['personalData.surname'] = $surname;
        if (!is_null($email)) $params['personalData.email'] = $email;

        $params['sign'] = sha1(implode($params) . $this->key);

        $response = $this->doRequest('https://pay.cashbill.pl/' . ($this->test_environment ? 'testws' : 'ws') . '/rest/payment/' . $this->shop_id, [
            'form_params' => $params,
            'headers' => [
                'Content-Type' => 'application/x-www-form-urlencoded; charset=UTF8'
            ]
        ], 'POST', false, false);

        if ($response->getStatusCode() != 200) {
            throw new TransferException('cashbill error ' . $response->getBody());
        }

        $json = json_decode($response->getBody());
        if (isset($json->id) && isset($json->redirectUrl)) {
            $this->payment_generated = true;
            $this->payment_id = $json->id;
            $this->payment_url = $json->redirectUrl;

            return true;
        }
        return false;
    }

    public function setPaymentUrls($returnUrl, $negativeReturnUrl, $transaction_id = NULL): bool
    {
        if (is_null($transaction_id)) $transaction_id = $this->payment_id;

        $response = $this->doRequest('https://pay.cashbill.pl/' . ($this->test_environment ? 'testws' : 'ws') . '/rest/payment/' . $this->shop_id . '/' . $transaction_id, [
            'form_params' => [
                'returnUrl' => $returnUrl,
                'negativeReturnUrl' => $negativeReturnUrl,
                'sign' => sha1($transaction_id . $returnUrl . $negativeReturnUrl . $this->key)
            ],
            'headers' => [
                'Content-Type' => 'application/x-www-form-urlencoded; charset=UTF8'
            ]
        ], 'PUT', false, false);

        if ($response->getStatusCode() != 204) {
            throw new TransferException('cashbill error ' . $response->getBody());
        }
        return true;
    }

    public function getTransactionInfo($transaction_id = NULL)
    {
        if (is_null($transaction_id)) $transaction_id = $this->payment_id;

        $response = $this->doRequest('https://pay.cashbill.pl/' . ($this->test_environment ? 'testws' : 'ws') . '/rest/payment/' . $this->shop_id . '/' . $transaction_id.'?sign='.sha1($transaction_id . $this->key), [], 'GET', false, false);

        if ($response->getStatusCode() != 200) {
            throw new TransferException('cashbill error ' . $response->getBody());
        }
        return json_decode($response->getBody());
    }

    public function getTransactionId(): ?string
    {
        if (!$this->payment_generated) {
            throw new TransferException('payment is not generated');
        }

        return $this->payment_id;
    }

    public function getTransactionUrl(): ?string
    {
        if (!$this->payment_generated) {
            throw new TransferException('payment is not generated');
        }

        return $this->payment_url;
    }
}