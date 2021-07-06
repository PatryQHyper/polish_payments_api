<?php

namespace PatryQHyper\Payments\DirectBilling;

use PatryQHyper\Payments\Exceptions\DirectBillingException;
use PatryQHyper\Payments\WebClient;

class PaybylinkDirectBilling extends WebClient
{
    private string $login;
    private string $password;
    private string $hash;

    private bool $is_generated = false;
    private ?string $payment_url;

    public function __construct(string $login, string $password, string $hash)
    {
        $this->login = $login;
        $this->password = $password;
        $this->hash = $hash;
    }

    public function generate(float $price, string $description, string $control)
    {
        $data = [
            'price' => $price * 100,
            'description' => $description,
            'control' => $control,
        ];
        $data['signature'] = hash('sha256', implode('|', $data) . '|' . $this->hash);

        $result = $this->doRequest('https://paybylink.pl/direct-biling/', [
            'json' => $data,
            'auth' => [
                $this->login,
                $this->password
            ]
        ], 'POST');

        if ($result->status == 'success')
        {
            $this->is_generated = true;
            $this->payment_url = $result->clientURL;

            return true;
        }

        throw new DirectBillingException('paybylink returned error '.$result->message);
    }

    public function getTransactionUrl(): ?string
    {
        if (!$this->is_generated) {
            throw new DirectBillingException('payment is not generated');
        }

        return $this->payment_url;
    }
}