<?php

/**
 * Created with love by: Patryk Vizauer (patryqhyper.pl)
 * Date: 21.05.2022 10:03
 * Using: PhpStorm
 */

namespace PatryQHyper\Payments\Online\Methods;

use PatryQHyper\Payments\Exceptions\PaymentException;
use PatryQHyper\Payments\Online\PaymentAbstract;
use PatryQHyper\Payments\Online\PaymentGeneratedResponse;

class OpenPayUPayment extends PaymentAbstract
{
    private int $posId;
    private string $md5key;
    private int $clientId;
    private string $clientSecret;
    private bool $sandbox;

    private string $openPayUUrl = 'https://secure.payu.com';

    private string $oauthToken;

    private string $productName;
    private int $productUnitPrice;
    private int $productQuantity;

    private array $products = [];

    private int $totalPrice = 0;

    private array $buyer = ['language' => 'pl'];

    private string $notifyUrl;
    private string $customerIp;
    private string $description;
    private string $currencyCode = 'PLN';
    private string $extOrderId;
    private string $visibleDescription;
    private string $continueUrl;

    public const ENVIRONMENT_PRODUCTION = false;
    public const ENVIRONMENT_SANDBOX = true;

    public function __construct(int $posId, string $md5Key, int $clientId, string $clientSecret, bool $sandbox = false)
    {
        $this->posId = $posId;
        $this->md5key = $md5Key;
        $this->clientId = $clientId;
        $this->clientSecret = $clientSecret;
        $this->sandbox = $sandbox;

        if ($sandbox)
            $this->openPayUUrl = 'https://secure.snd.payu.com';
    }

    /**
     * @throws PaymentException
     */
    public function oauthAuthorize(): void
    {
        $request = $this->doRequest($this->openPayUUrl . '/pl/standard/oauth/authorize', [
            'form_params' => [
                'grant_type' => 'client_credentials',
                'client_id' => $this->clientId,
                'client_secret' => $this->clientSecret
            ]
        ], 'POST', false, false);

        $json = json_decode($request->getBody());
        if ($request->getStatusCode() != 200)
            throw new PaymentException(sprintf('OpenPayU oauth error %s: %s', $json->error, $json->error_description));

        $this->oauthToken = $json->access_token;
    }

    public function getOauthToken(): string
    {
        return $this->oauthToken;
    }

    public function setProductName(string $productName): OpenPayUPayment
    {
        $this->productName = $productName;
        return $this;
    }

    public function setProductUnitPrice(float $price): OpenPayUPayment
    {
        $this->productUnitPrice = $price * 100;
        return $this;
    }

    public function setProductQuantity(int $quantity): OpenPayUPayment
    {
        $this->productQuantity = $quantity;
        return $this;
    }

    public function addProduct(): void
    {
        $this->products[] = [
            'name' => $this->productName,
            'unitPrice' => $this->productUnitPrice,
            'quantity' => $this->productQuantity
        ];

        $this->totalPrice += $this->productUnitPrice;
    }

    public function setBuyerEmail(string $email): OpenPayUPayment
    {
        $this->buyer['email'] = $email;
        return $this;
    }

    public function setBuyerLanguage(string $language): OpenPayUPayment
    {
        $this->buyer['language'] = $language;
        return $this;
    }

    public function setBuyerPhone(string $phone): OpenPayUPayment
    {
        $this->buyer['phone'] = $phone;
        return $this;
    }

    public function setBuyerFirstname(string $firstname): OpenPayUPayment
    {
        $this->buyer['firstName'] = $firstname;
        return $this;
    }

    public function setBuyerLastname(string $lastname): OpenPayUPayment
    {
        $this->buyer['lastName'] = $lastname;
        return $this;
    }

    public function setNotifyUrl(string $notifyUrl): OpenPayUPayment
    {
        $this->notifyUrl = $notifyUrl;
        return $this;
    }

    public function setCustomerIp(string $customerIp): OpenPayUPayment
    {
        $this->customerIp = $customerIp;
        return $this;
    }

    public function setDescription(string $description): OpenPayUPayment
    {
        $this->description = $description;
        return $this;
    }

    public function setCurrencyCode(string $currencyCode): OpenPayUPayment
    {
        $this->currencyCode = $currencyCode;
        return $this;
    }

    public function setExtOrderId(string $extOrderId): OpenPayUPayment
    {
        $this->extOrderId = $extOrderId;
        return $this;
    }

    public function setVisibleDescription(string $visibleDescription): OpenPayUPayment
    {
        $this->visibleDescription = $visibleDescription;
        return $this;
    }

    public function setContinueUrl(string $continueUrl): OpenPayUPayment
    {
        $this->continueUrl = $continueUrl;
        return $this;
    }

    /**
     * @throws PaymentException
     */
    public function generatePayment(): PaymentGeneratedResponse
    {
        $array['merchantPosId'] = $this->posId;
        $array['notifyUrl'] = $this->notifyUrl;
        $array['customerIp'] = $this->customerIp;
        $array['description'] = $this->description;
        $array['currencyCode'] = $this->currencyCode;
        $array['totalAmount'] = $this->totalPrice;
        $array['buyer'] = $this->buyer;
        $array['products'] = $this->products;

        if (isset($this->extOrderId)) $array['extOrderId'] = $this->extOrderId;
        if (isset($this->visibleDescription)) $array['visibleDescription'] = $this->visibleDescription;
        if (isset($this->continueUrl)) $array['continueUrl'] = $this->continueUrl;

        $request = $this->doRequest($this->openPayUUrl . '/api/v2_1/orders', [
            'headers' => [
                'Content-type' => 'application/json',
                'Accept' => 'application/json',
                'Authorization' => 'Bearer ' . $this->oauthToken
            ],
            'json' => $array,
            'allow_redirects' => false
        ], 'POST', false, false);

        if (!in_array($request->getStatusCode(), [200, 201, 301, 302]))
            throw new PaymentException('OpenPayU payment error: ' . $request->getBody());

        $json = json_decode($request->getBody());
        return new PaymentGeneratedResponse(
            $json->redirectUri,
            $json->orderId,
        );
    }

    public function verifySignature(string $header, string $payload): bool
    {
        $parsedHeader = $this->parseSignatureHeader($header);
        if (isset($parsedHeader['signature'])) {
            if ($parsedHeader['algorithm'] == 'MD5')
                $hash = md5($payload . $this->md5key);
            else if (in_array($parsedHeader['algorithm'], ['SHA', 'SHA1', 'SHA-1']))
                $hash = sha1($payload . $this->md5key);
            else
                $hash = hash('sha256', $payload . $this->md5key);

            if (strcmp($parsedHeader['signature'], $hash) == 0) {
                return true;
            }
        }
        return false;
    }

    protected function parseSignatureHeader(string $header): ?array
    {
        if (empty($header)) {
            return null;
        }

        $signatureData = [];

        $list = explode(';', rtrim($header, ';'));
        if (empty($list)) {
            return null;
        }

        foreach ($list as $value) {
            $explode = explode('=', $value);
            if (count($explode) != 2) {
                return null;
            }
            $signatureData[$explode[0]] = $explode[1];
        }

        return $signatureData;
    }
}