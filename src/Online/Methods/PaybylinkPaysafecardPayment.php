<?php

namespace PatryQHyper\Payments\Online\Methods;

use PatryQHyper\Payments\Exceptions\PaymentException;
use PatryQHyper\Payments\Online\PaymentAbstract;
use PatryQHyper\Payments\Online\PaymentGeneratedResponse;

class PaybylinkPaysafecardPayment extends PaymentAbstract
{
    private int $userId;
    private int $shopId;
    private string $pin;

    private float $amount;
    private string $redirectSuccessUrl;
    private string $redirectFailUrl;
    private string $notifyUrl;
    private string $control;
    private string $description;

    public function __construct(int $userId, int $shopId, string $pin)
    {
        $this->userId = $userId;
        $this->shopId = $shopId;
        $this->pin = $pin;
    }

    public function setAmount(float $amount): self
    {
        $this->amount = $amount;
        return $this;
    }

    public function setRedirectSuccessUrl(string $redirectSuccessUrl): self
    {
        $this->redirectSuccessUrl = $redirectSuccessUrl;
        return $this;
    }

    public function setRedirectFailUrl(string $redirectFailUrl): self
    {
        $this->redirectFailUrl = $redirectFailUrl;
        return $this;
    }

    public function setNotifyUrl(string $notifyUrl): self
    {
        $this->notifyUrl = $notifyUrl;
        return $this;
    }

    public function setControl(string $control): self
    {
        $this->control = $control;
        return $this;
    }

    public function setDescription(string $description): self
    {
        $this->description = $description;
        return $this;
    }

    public function generatePayment(): PaymentGeneratedResponse
    {
        $data = [
            'userid' => $this->userId,
            'shopid' => $this->shopId,
            'amount' => $this->amount,
            'return_ok' => $this->redirectSuccessUrl,
            'return_fail' => $this->redirectFailUrl,
            'url' => $this->notifyUrl,
            'control' => $this->control,
            'hash' => hash('sha256', $this->userId . $this->pin . $this->amount),
            'get_pid' => true,
        ];

        if (isset($this->description)) {
            $data['description'] = $this->description;
        }

        $request = $this->doRequest('https://paybylink.pl/api/psc/', [
            'form_params' => $data
        ], 'POST', false, false);

        $json = @json_decode($request->getBody());
        if (!$json) {
            throw new PaymentException('Paybylink error: ' . $request->getBody());
        }

        if (!$json->status) {
            throw new PaymentException('Paybylink error: ' . $json->message);
        }

        return new PaymentGeneratedResponse(
            'https://paybylink.pl/pay/' . $json->pid,
            $json->pid
        );
    }
}