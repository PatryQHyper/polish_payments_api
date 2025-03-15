<?php

namespace PatryQHyper\Payments\Online\Methods;

use PatryQHyper\Payments\Exceptions\PaymentException;
use PatryQHyper\Payments\Helpers\ArrayHelper;
use PatryQHyper\Payments\Online\PaymentAbstract;
use PatryQHyper\Payments\Online\PaymentGeneratedResponse;

class SimPayDirectBillingPayment extends PaymentAbstract
{
    private ?string $apiKey;
    private string $apiPassword;
    private string $serviceId;
    private string $serviceHash;

    private float $amount;
    private string $amountType = 'gross';
    private string $description;
    private string $control;
    private string $returnSuccess;
    private string $returnFail;
    private string $phoneNumber;
    private string $steamId;
    private string $email;

    public function __construct(?string $apiKey, string $apiPassword, string $serviceId, string $serviceHash)
    {
        $this->apiKey = $apiKey;
        $this->apiPassword = $apiPassword;
        $this->serviceId = $serviceId;
        $this->serviceHash = $serviceHash;
    }

    public function setAmount(float $amount): SimPayDirectBillingPayment
    {
        $this->amount = $amount;
        return $this;
    }

    public function setAmountType(string $amountType): SimPayDirectBillingPayment
    {
        $this->amountType = $amountType;
        return $this;
    }

    public function setDescription(string $description): SimPayDirectBillingPayment
    {
        $this->description = $description;
        return $this;
    }

    public function setControl(string $control): SimPayDirectBillingPayment
    {
        $this->control = $control;
        return $this;
    }

    public function setReturnSuccess(string $returnSuccess): SimPayDirectBillingPayment
    {
        $this->returnSuccess = $returnSuccess;
        return $this;
    }

    public function setReturnFail(string $returnFail): SimPayDirectBillingPayment
    {
        $this->returnFail = $returnFail;
        return $this;
    }

    public function setPhoneNumber(string $phoneNumber): SimPayDirectBillingPayment
    {
        $this->phoneNumber = $phoneNumber;
        return $this;
    }

    public function setSteamId(string $steamId): SimPayDirectBillingPayment
    {
        $this->steamId = $steamId;
        return $this;
    }

    public function setEmail(string $email): SimPayDirectBillingPayment
    {
        $this->email = $email;
        return $this;
    }

    /**
     * @throws PaymentException
     */
    public function generatePayment(): PaymentGeneratedResponse
    {
        $array['amount'] = $this->amount;
        $array['amountType'] = $this->amountType;

        if (isset($this->description)) {
            $array['description'] = $this->description;
        }

        if (isset($this->control)) {
            $array['control'] = $this->control;
        }

        if (isset($this->returnSuccess)) {
            $array['returns']['success'] = $this->returnSuccess;
        }

        if (isset($this->returnFail)) {
            $array['returns']['failure'] = $this->returnFail;
        }

        if (isset($this->phoneNumber)) {
            $array['phoneNumber'] = $this->phoneNumber;
        }

        if (isset($this->steamId)) {
            $array['steamid'] = $this->steamId;
        }

        if (isset($this->email)) {
            $array['email'] = $this->email;
        }

        $request = $this->doRequest(sprintf('https://api.simpay.pl/directbilling/%s/transactions', $this->serviceId), [
            'json' => $array,
            'headers' => [
                'Authorization' => 'Bearer ' . $this->apiPassword,
            ],
        ], 'POST', false, false);

        if ($request->getStatusCode() !== 200) {
            throw new PaymentException(sprintf('SimPay error: %s', $request->getBody()));
        }

        $json = json_decode($request->getBody());

        return new PaymentGeneratedResponse(
            $json->data->redirectUrl,
            $json->data->transactionId,
        );
    }

    public function generateIpnSignature(array $payload): string
    {
        unset($payload['signature']);

        $data = ArrayHelper::flatten($payload);
        $data[] = $this->serviceHash;

        return hash('sha256', implode('|', $data));
    }
}