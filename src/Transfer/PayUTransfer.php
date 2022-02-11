<?php

/**
 * Created with love by: PatryQHyper.pl
 * Date: 11.02.2022 08:04
 * Using: PhpStorm
 */

namespace PatryQHyper\Payments\Transfer;

use PatryQHyper\Payments\Exceptions\TransferException;
use PatryQHyper\Payments\WebClient;

class PayUTransfer extends WebClient
{
    private int $posId;
    private string $md5;
    private int $clientId;
    private string $clientSecret;
    private bool $sandbox;

    private string $payuUrl = 'https://secure.payu.com';

    private ?string $oauthToken = null;

    private array $products = [];

    private array $buyer = [];

    private int $totalAmount = 0;

    private ?string $paymentId = null;
    private ?string $paymentUrl = null;

    public function __construct(int $posId, string $md5, int $clientId, string $clientSecret, bool $sandbox = false)
    {
        $this->posId = $posId;
        $this->md5 = $md5;
        $this->clientId = $clientId;
        $this->clientSecret = $clientSecret;
        $this->sandbox = $sandbox;

        if ($sandbox)
            $this->payuUrl = 'https://secure.snd.payu.com';
    }

    public function oauthAuthorize()
    {
        $request = $this->doRequest($this->payuUrl . '/pl/standard/oauth/authorize', [
            'form_params' => [
                'grant_type' => 'client_credentials',
                'client_id' => $this->clientId,
                'client_secret' => $this->clientSecret
            ]
        ], 'POST', false, false);

        $json = json_decode($request->getBody());
        if ($request->getStatusCode() != 200)
            throw new TransferException('PayU error [oauth]: ' . $json->error . ':' . $json->error_description);

        $this->oauthToken = $json->access_token;
    }

    public function addProduct(string $name, float $unitPrice, int $quantity)
    {
        $this->products[] = [
            'name' => $name,
            'unitPrice' => ($unitPrice * 100),
            'quantity' => $quantity
        ];

        $this->totalAmount += ($unitPrice * 100);
    }

    public function setBuyer(string $email, ?string $phone = null, ?string $firstname = null, ?string $surname = null, string $language = 'pl')
    {
        $this->buyer['email'] = $email;
        $this->buyer['language'] = $language;

        if ($phone) $this->buyer['phone'] = $phone;
        if ($firstname) $this->buyer['firstName'] = $firstname;
        if ($surname) $this->buyer['lastName'] = $surname;
    }

    public function generatePayment(
        string  $notifyUrl,
        string  $customerIp,
        string  $description,
        ?string $extOrderId = null,
        string  $currencyCode = 'PLN',
        ?string $visibleDescription = null,
        ?string $continueUrl = null
    )
    {

        $totalAmount = 0;
        foreach ($this->products as $product) {
            $totalAmount += $product['unitPrice'];
        }

        $data = [
            'notifyUrl' => $notifyUrl,
            'customerIp' => $customerIp,
            'description' => $description,
            'currencyCode' => $currencyCode,
            'merchantPosId' => $this->posId,
            'totalAmount' => $totalAmount,
            'buyer' => $this->buyer,
            'products' => $this->products
        ];

        if ($extOrderId) $data['extOrderId'] = $extOrderId;
        if ($visibleDescription) $data['visibleDescription'] = $visibleDescription;
        if ($continueUrl) $data['continueUrl'] = urlencode($continueUrl);

        $request = $this->doRequest($this->payuUrl . '/api/v2_1/orders', [
            'headers' => [
                'Content-type' => 'application/json',
                'Authorization' => 'Bearer ' . $this->oauthToken
            ],
            'allow_redirects' => false,
            'json' => $data
        ], 'POST', false, false);

        if (!in_array($request->getStatusCode(), [200, 201, 301, 302]))
            throw new TransferException('PayU error [payment]: ' . $request->getBody());

        $json = json_decode($request->getBody());
        $this->paymentId = $json->orderId;
        $this->paymentUrl = $json->redirectUri;

        return true;
    }

    public function getOauthToken(): ?string
    {
        return $this->oauthToken;
    }

    public function getPaymentId(): ?string
    {
        return $this->paymentId;
    }

    public function getPaymentUrl(): ?string
    {
        return $this->paymentUrl;
    }
}