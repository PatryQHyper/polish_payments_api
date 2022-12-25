<?php

/**
 * Created with love by: Patryk Vizauer (wizjoner.dev)
 * Date: 27.11.2022 13:18
 * Using: PhpStorm
 */

namespace PatryQHyper\Payments\Online\Methods;

use PatryQHyper\Payments\Exceptions\PaymentException;
use PatryQHyper\Payments\Online\PaymentAbstract;
use PatryQHyper\Payments\Online\PaymentGeneratedResponse;

class IMojePayment extends PaymentAbstract
{
    private string $merchantId;
    private string $serviceId;
    private string $apiToken;
    private bool $useSandbox;
    private string $iMojeUrl = 'https://api.imoje.pl/v1/merchant';

    public const ENVIRONMENT_SANDBOX = true;
    public const ENVIRONMENT_PRODUCTION = false;

    private int $amount;
    private string $currency = 'PLN';
    private string $orderId;
    private ?string $title;
    private ?array $visibleMethod;
    private ?string $returnUrl;
    private ?string $successReturnUrl;
    private ?string $failureReturnUrl;
    private ?string $simp;
    private ?int $validTo;

    private array $customer = ['locale' => 'pl'];

    public function __construct(string $merchantId, string $serviceId, string $apiToken, bool $useSandbox = self::ENVIRONMENT_PRODUCTION)
    {
        $this->merchantId = $merchantId;
        $this->serviceId = $serviceId;
        $this->apiToken = $apiToken;
        $this->useSandbox = $useSandbox;

        if ($this->useSandbox) {
            $this->iMojeUrl = 'https://sandbox.api.imoje.pl/v1/merchant';
        }
    }

    public function setAmount(float $amount): IMojePayment
    {
        $this->amount = $amount * 100;
        return $this;
    }

    public function setCurrency(string $currency): IMojePayment
    {
        $this->currency = $currency;
        return $this;
    }

    public function setOrderId(string $orderId): IMojePayment
    {
        $this->orderId = $orderId;
        return $this;
    }

    public function setTitle(?string $title): IMojePayment
    {
        $this->title = $title;
        return $this;
    }

    public function setVisibleMethod(?array $visibleMethod): IMojePayment
    {
        $this->visibleMethod = $visibleMethod;
        return $this;
    }

    public function setReturnUrl(?string $returnUrl): IMojePayment
    {
        $this->returnUrl = $returnUrl;
        return $this;
    }

    public function setSuccessReturnUrl(?string $successReturnUrl): IMojePayment
    {
        $this->successReturnUrl = $successReturnUrl;
        return $this;
    }

    public function setFailureReturnUrl(?string $failureReturnUrl): IMojePayment
    {
        $this->failureReturnUrl = $failureReturnUrl;
        return $this;
    }

    public function setSimp(?string $simp): IMojePayment
    {
        $this->simp = $simp;
        return $this;
    }

    public function setValidTo(?int $validTo): IMojePayment
    {
        $this->validTo = $validTo;
        return $this;
    }

    public function setCustomerFirstName(string $firstName): IMojePayment
    {
        $this->customer['firstName'] = $firstName;
        return $this;
    }

    public function setCustomerLastName(string $lastName): IMojePayment
    {
        $this->customer['lastName'] = $lastName;
        return $this;
    }

    public function setCustomerEmail(string $email): IMojePayment
    {
        $this->customer['email'] = $email;
        return $this;
    }

    public function setCustomerPhone(string $phone): IMojePayment
    {
        $this->customer['phone'] = $phone;
        return $this;
    }

    public function setCustomerLocale(string $locale): IMojePayment
    {
        $this->customer['locale'] = $locale;
        return $this;
    }

    public function generatePayment()
    {
        $payload['serviceId'] = $this->serviceId;
        $payload['amount'] = $this->amount;
        $payload['currency'] = $this->currency;
        $payload['orderId'] = $this->orderId;
        $payload['customer'] = $this->customer;
        if (isset($this->title)) {
            $payload['title'] = $this->title;
        }
        if (isset($this->visibleMethod)) {
            $payload['visibleMethod'] = $this->visibleMethod;
        }
        if (isset($this->returnUrl)) {
            $payload['returnUrl'] = $this->returnUrl;
        }
        if (isset($this->successReturnUrl)) {
            $payload['successReturnUrl'] = $this->successReturnUrl;
        }
        if (isset($this->failureReturnUrl)) {
            $payload['failureReturnUrl'] = $this->failureReturnUrl;
        }
        if (isset($this->simp)) {
            $payload['simp'] = $this->simp;
        }
        if (isset($this->validTo)) {
            $payload['validTo'] = $this->validTo;
        }

        $request = $this->doRequest(sprintf('%s/%s/payment', $this->iMojeUrl, $this->merchantId), [
            'headers' => [
                'Accept' => 'application/json',
                'Authorization' => 'Bearer ' . $this->apiToken,
                'Content-Type' => 'application/json',
                'Cache-Control' => 'no-cache',
            ],
            'json' => $payload,
        ], 'POST', false, false);

        $json = json_decode($request->getBody());
        if ($request->getStatusCode() == 200) {
            return new PaymentGeneratedResponse($json->payment->url, $json->payment->id);
        }

        throw new PaymentException(sprintf('iMoje error [%s]: %s', $request->getStatusCode(), $request->getBody()));
    }

    public function verifySignature(string $header, string $payload, string $serviceKey): bool
    {
        $parsedHeader = $this->parseSignatureHeader($header);
        if (isset($parsedHeader['signature'])) {
            if (strtoupper($parsedHeader['alg']) == 'MD5')
                $hash = md5($payload . $serviceKey);
            else if (in_array(strtoupper($parsedHeader['alg']), ['SHA', 'SHA1', 'SHA-1']))
                $hash = sha1($payload . $serviceKey);
            else
                $hash = hash(strtolower($parsedHeader['alg']), $payload . $serviceKey);

            if (strcmp($parsedHeader['signature'], $hash) == 0) {
                return true;
            }

            if($parsedHeader['merchantid'] == $this->merchantId) {
                return true;
            }

            if($parsedHeader['serviceid'] == $this->serviceId) {
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