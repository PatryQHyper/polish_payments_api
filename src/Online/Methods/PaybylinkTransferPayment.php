<?php

namespace PatryQHyper\Payments\Online\Methods;

use PatryQHyper\Payments\Exceptions\PaymentException;
use PatryQHyper\Payments\Online\PaymentAbstract;
use PatryQHyper\Payments\Online\PaymentGeneratedResponse;

class PaybylinkTransferPayment extends PaymentAbstract
{
    private int $shopId;
    private string $hash;

    private float $amount;
    private string $control;
    private string $description;
    private string $email;
    private string $notifyUrl;
    private string $returnUrlSuccess;
    private bool $returnUrlSuccessTidPass;
    private bool $hideReceiver;
    private string $customFinishNote;

    public function __construct(int $shopId, string $hash)
    {
        $this->shopId = $shopId;
        $this->hash = $hash;
    }

    public function setAmount(float $amount): self
    {
        $this->amount = $amount;
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

    public function setEmail(string $email): self
    {
        $this->email = $email;
        return $this;
    }

    public function setNotifyUrl(string $notifyUrl): self
    {
        $this->notifyUrl = $notifyUrl;
        return $this;
    }

    public function setReturnUrlSuccess(string $returnUrlSuccess): self
    {
        $this->returnUrlSuccess = $returnUrlSuccess;
        return $this;
    }

    public function setReturnUrlSuccessTidPass(bool $returnUrlSuccessTidPass): self
    {
        $this->returnUrlSuccessTidPass = $returnUrlSuccessTidPass;
        return $this;
    }

    public function setHideReceiver(bool $hideReceiver): self
    {
        $this->hideReceiver = $hideReceiver;
        return $this;
    }

    public function setCustomFinishNote(string $customFinishNote): self
    {
        $this->customFinishNote = $customFinishNote;
        return $this;
    }

    public function generatePayment()
    {
        $params['shopId'] = $this->shopId;
        $params['price'] = sprintf('%.2f', $this->amount);
        if (isset($this->control)) {
            $params['control'] = $this->control;
        }

        if (isset($this->description)) {
            $params['description'] = $this->description;
        }

        if (isset($this->email)) {
            $params['email'] = $this->email;
        }

        if (isset($this->notifyUrl)) {
            $params['notifyURL'] = $this->notifyUrl;
        }

        if (isset($this->returnUrlSuccess)) {
            $params['returnUrlSuccess'] = $this->returnUrlSuccess;
        }

        if (isset($this->returnUrlSuccessTidPass)) {
            $params['returnUrlSuccessTidPass'] = $this->returnUrlSuccessTidPass;
        }

        if (isset($this->hideReceiver)) {
            $params['hideReceiver'] = $this->hideReceiver;
        }

        if (isset($this->customFinishNote)) {
            $params['customFinishNote'] = $this->customFinishNote;
        }

        $params['signature'] = hash('sha256', $this->hash . '|' . implode('|', $params));

        $request = $this->doRequest('https://secure.pbl.pl/api/v1/transfer/generate', [
            'json' => $params
        ], 'POST', false, false);

        if ($request->getStatusCode() !== 200)
            throw new PaymentException('Paybylink error: ' . $request->getBody());

        $json = json_decode($request->getBody());

        return new PaymentGeneratedResponse($json->url, $json->transactionId);
    }

    public function generateNotificationHash(array $data): string
    {
        $array = [
            $this->hash,
            $data['transactionId'],
            $data['control'],
            $data['email'],
            sprintf('%.2f', $data['amountPaid']),
            $data['notificationAttempt'],
            $data['paymentType'],
            $data['apiVersion'],
        ];

        return hash('sha256', implode('|', $array));
    }
}