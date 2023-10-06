<?php

/**
 * Created with love by: Patryk Vizauer (patryqhyper.pl)
 * Date: 21.05.2022 11:00
 * Using: PhpStorm
 */

namespace PatryQHyper\Payments\Online\Methods;

use PatryQHyper\Payments\Exceptions\PaymentException;
use PatryQHyper\Payments\Online\PaymentAbstract;
use PatryQHyper\Payments\Online\PaymentGeneratedResponse;

class Przelewy24Payment extends PaymentAbstract
{
    private int $merchantId;
    private int $posId;
    private string $crc;
    private string $raportKey;
    private bool $useSandbox = false;

    public const ENVIRONMENT_SANDBOX = true;
    public const ENVIRONMENT_PRODUCTION = false;

    private string $sessionId;
    private int $amount;
    private string $currency = 'PLN';
    private string $description;
    private string $email;
    private string $country = 'pl';
    private string $client;
    private string $address;
    private string $zip;
    private string $city;
    private string $phone;
    private string $language = 'pl';
    private string $method;
    private string $urlReturn;
    private string $urlStatus;
    private int $timeLimit;
    private int $channel;
    private bool $waitForResult;
    private bool $regulationAccept;
    private int $shipping;
    private string $transferLabel;
    private string $methodRefId;
    private array $additional;

    public function __construct(int $merchantId, int $posId, string $crc, string $raportKey, bool $useSandbox = false)
    {
        $this->merchantId = $merchantId;
        $this->posId = $posId;
        $this->crc = $crc;
        $this->raportKey = $raportKey;
        $this->useSandbox = $useSandbox;
    }

    public function setSessionId(string $sessionId): Przelewy24Payment
    {
        $this->sessionId = $sessionId;
        return $this;
    }

    public function setAmount(float $amount): Przelewy24Payment
    {
        $this->amount = $amount * 100;
        return $this;
    }

    public function setCurrency(string $currency): Przelewy24Payment
    {
        $this->currency = $currency;
        return $this;
    }

    public function setDescription(string $description): Przelewy24Payment
    {
        $this->description = $description;
        return $this;
    }

    public function setEmail(string $email): Przelewy24Payment
    {
        $this->email = $email;
        return $this;
    }

    public function setCountry(string $country): Przelewy24Payment
    {
        $this->country = $country;
        return $this;
    }

    public function setClient(string $client): Przelewy24Payment
    {
        $this->client = $client;
        return $this;
    }

    public function setAddress(string $address): Przelewy24Payment
    {
        $this->address = $address;
        return $this;
    }

    public function setZip(string $zip): Przelewy24Payment
    {
        $this->zip = $zip;
        return $this;
    }

    public function setCity(string $city): Przelewy24Payment
    {
        $this->city = $city;
        return $this;
    }

    public function setPhone(string $phone): Przelewy24Payment
    {
        $this->phone = $phone;
        return $this;
    }

    public function setLanguage(string $language): Przelewy24Payment
    {
        $this->language = $language;
        return $this;
    }

    public function setMethod(string $method): Przelewy24Payment
    {
        $this->method = $method;
        return $this;
    }

    public function setUrlReturn(string $urlReturn): Przelewy24Payment
    {
        $this->urlReturn = $urlReturn;
        return $this;
    }

    public function setUrlStatus(string $urlStatus): Przelewy24Payment
    {
        $this->urlStatus = $urlStatus;
        return $this;
    }

    public function setTimeLimit(int $timeLimit): Przelewy24Payment
    {
        $this->timeLimit = $timeLimit;
        return $this;
    }

    public function setChannel(int $channel): Przelewy24Payment
    {
        $this->channel = $channel;
        return $this;
    }

    public function setWaitForResult(bool $waitForResult): Przelewy24Payment
    {
        $this->waitForResult = $waitForResult;
        return $this;
    }

    public function setRegulationAccept(bool $regulationAccept): Przelewy24Payment
    {
        $this->regulationAccept = $regulationAccept;
        return $this;
    }

    public function setShipping(int $shipping): Przelewy24Payment
    {
        $this->shipping = $shipping;
        return $this;
    }

    public function setTransferLabel(string $transferLabel): Przelewy24Payment
    {
        $this->transferLabel = $transferLabel;
        return $this;
    }

    public function setMethodRefId(string $methodRefId): Przelewy24Payment
    {
        $this->methodRefId = $methodRefId;
        return $this;
    }

    public function setAdditional(array $additional): Przelewy24Payment
    {
        $this->additional = $additional;
        return $this;
    }

    /**
     * @throws PaymentException
     */
    public function generatePayment(): PaymentGeneratedResponse
    {
        $array['merchantId'] = $this->merchantId;
        $array['posId'] = $this->posId;
        $array['sessionId'] = $this->sessionId;
        $array['amount'] = $this->amount;
        $array['currency'] = $this->currency;
        $array['description'] = $this->description;
        $array['email'] = $this->email;
        if (isset($this->client)) $array['client'] = $this->client;
        if (isset($this->address)) $array['address'] = $this->address;
        if (isset($this->zip)) $array['zip'] = $this->zip;
        if (isset($this->city)) $array['city'] = $this->city;
        if (isset($this->country)) $array['country'] = $this->country;
        if (isset($this->phone)) $array['phone'] = $this->phone;
        if (isset($this->language)) $array['language'] = $this->language;
        if (isset($this->method)) $array['method'] = $this->method;
        if (isset($this->urlReturn)) $array['urlReturn'] = $this->urlReturn;
        if (isset($this->urlStatus)) $array['urlStatus'] = $this->urlStatus;
        if (isset($this->timeLimit)) $array['timeLimit'] = $this->timeLimit;
        if (isset($this->channel)) $array['channel'] = $this->channel;
        if (isset($this->waitForResult)) $array['waitForResult'] = $this->waitForResult;
        if (isset($this->regulationAccept)) $array['regulationAccept'] = $this->regulationAccept;
        if (isset($this->shipping)) $array['shipping'] = $this->shipping;
        if (isset($this->transferLabel)) $array['transferLabel'] = $this->transferLabel;
        if (isset($this->methodRefId)) $array['methodRefId'] = $this->methodRefId;
        if (isset($this->additional)) $array['additional'] = $this->additional;

        $array['sign'] = hash('sha384', json_encode([
            'sessionId' => $this->sessionId,
            'merchantId' => $this->merchantId,
            'amount' => $this->amount,
            'currency' => $this->currency,
            'crc' => $this->crc
        ], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES));

        $request = $this->doRequest(sprintf('https://%s.przelewy24.pl/api/v1/transaction/register', ($this->useSandbox ? 'sandbox' : 'secure')), [
            'auth' => [
                $this->posId,
                $this->raportKey
            ],
            'json' => $array
        ], 'POST', false, false);

        if ($request->getStatusCode() != 200)
            throw new PaymentException(sprintf('Przelewy24 error [%s]: %s', $request->getStatusCode(), $request->getBody()));

        $json = json_decode($request->getBody());

        return new PaymentGeneratedResponse(
            sprintf('https://%s.przelewy24.pl/trnRequest/%s', ($this->useSandbox ? 'sandbox' : 'secure'), $json->data->token),
            $json->data->token
        );
    }

    /**
     * @throws PaymentException
     */
    public function verifyTransaction(int $orderId): bool
    {
        $array['merchantId'] = $this->merchantId;
        $array['posId'] = $this->posId;
        $array['sessionId'] = $this->sessionId;
        $array['amount'] = $this->amount;
        $array['currency'] = $this->currency;
        $array['orderId'] = $orderId;
        $array['sign'] = hash('sha384', json_encode([
            'sessionId' => $this->sessionId,
            'orderId' => $orderId,
            'amount' => $this->amount,
            'currency' => $this->currency,
            'crc' => $this->crc
        ], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE));

        $request = $this->doRequest(sprintf('https://%s.przelewy24.pl/api/v1/transaction/verify', ($this->useSandbox ? 'sandbox' : 'secure')), [
            'auth' => [
                $this->posId,
                $this->raportKey
            ],
            'json' => $array
        ], 'PUT', false, false);

        if ($request->getStatusCode() != 200)
            throw new PaymentException(sprintf('Przelewy24 error [%s]: %s', $request->getStatusCode(), $request->getBody()));

        return true;
    }
}