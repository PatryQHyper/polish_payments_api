<?php

namespace PatryQHyper\Payments\Online\Methods;

use PatryQHyper\Payments\Exceptions\PaymentException;
use PatryQHyper\Payments\Online\PaymentAbstract;
use PatryQHyper\Payments\Online\PaymentGeneratedResponse;

class PaybylinkDirectbillingPayment extends PaymentAbstract
{
    private string $login;
    private string $password;
    private string $hash;

    private float $amount;
    private string $description;
    private string $control;

    public function __construct(string $login, string $password, string $hash)
    {
        $this->login = $login;
        $this->password = $password;
        $this->hash = $hash;
    }

    public function setAmount(float $amount): self
    {
        $this->amount = $amount;
        return $this;
    }

    public function setDescription(string $description): self
    {
        $this->description = $description;
        return $this;
    }

    public function setControl(string $control): self
    {
        $this->control = $control;
        return $this;
    }

    public function generatePayment(): PaymentGeneratedResponse
    {
        $data = [
            'price' => $this->amount * 100,
            'description' => $this->description,
            'control' => $this->control
        ];

        $data['signature'] = hash('sha256', implode('|', $data) . '|' . $this->hash);

        $request = $this->doRequest('https://paybylink.pl/direct-biling/', [
            'json' => $data,
            'auth' => [
                $this->login,
                $this->password
            ]
        ], 'POST');

        if ($request->status === 'success') {
            return new PaymentGeneratedResponse($request->clientURL);
        }

        throw new PaymentException('Paybylink error: ' . $request->message);
    }

    public function getTransactionInfo(string $paymentId)
    {
        $request = $this->doRequest('https://paybylink.pl/direct-biling/transactionStatus.php', [
            'json' => [
                'pid' => $paymentId,
                'signature' => hash('sha256', $paymentId . '|' . $this->hash)
            ],
            'auth' => [
                $this->login,
                $this->password
            ]
        ], 'POST');

        if ($request->status === 'error' && isset($request->code) && isset($request->message)) {
            throw new PaymentException('Paybylink error ' . $request->code . ': ' . $request->message);
        }

        return $request;
    }
}