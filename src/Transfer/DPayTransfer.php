<?php

/**
 * Created with love by: PatryQHyper.pl
 * Date: 05.11.2021 23:14
 * Using: PhpStorm
 */

namespace PatryQHyper\Payments\Transfer;

use PatryQHyper\Payments\Exceptions\TransferException;
use PatryQHyper\Payments\WebClient;

class DPayTransfer extends WebClient
{
    private string $service_name;
    private string $secret_hash;
    private bool $test = false;

    private bool $payment_generated = false;
    private ?string $payment_id = NULL;
    private ?string $payment_url = NULL;

    public function __construct(string $service_name, string $secret_hash, bool $use_test = false)
    {
        $this->service_name = $service_name;
        $this->secret_hash = $secret_hash;
        $this->test = $use_test;
    }

    public function generate(
        float   $price,
        string  $successUrl,
        string  $failUrl,
        string  $ipnUrl,
        ?string $description = NULL,
        ?string $custom = NULL,
        ?bool   $installment = NULL,
        ?bool   $creditCard = NULL,
        ?bool   $paysafecard = NULL,
        ?bool   $paypal = NULL,
        ?bool   $noBanks = NULL,
        ?string $channel = NULL,
        ?string $email = NULL,
        ?string $client_name = NULL,
        ?string $client_surname = NULL,
        ?bool   $accept_tos = true,
        ?string $style = 'default'
    )
    {
        $data = [
            'service' => $this->service_name,
            'value' => $price,
            'url_success' => $successUrl,
            'url_fail' => $failUrl,
            'url_ipn' => $ipnUrl
        ];

        $data['checksum'] = hash('sha256', $data['service'] . '|' . $this->secret_hash . '|' . sprintf('%.2f', $data['value']) . '|' . $data['url_success'] . '|' . $data['url_fail'] . '|' . $data['url_ipn']);

        if(!is_null($installment)) $data['installment'] = $installment;
        if(!is_null($creditCard)) $data['creditcard'] = $creditCard;
        if(!is_null($paysafecard)) $data['paysafecard'] = $paysafecard;
        if(!is_null($paypal)) $data['paypal'] = $paypal;
        if(!is_null($noBanks)) $data['nobanks'] = $noBanks;
        if(!is_null($channel)) $data['channel'] = $channel;
        if(!is_null($email)) $data['email'] = $email;
        if(!is_null($client_name)) $data['client_name'] = $client_name;
        if(!is_null($client_surname)) $data['client_surname'] = $client_surname;
        if(!is_null($accept_tos)) $data['accept_tos'] = $accept_tos;
        if(!is_null($description)) $data['description'] = $description;
        if(!is_null($custom)) $data['custom'] = $custom;
        if(!is_null($style)) $data['style'] = $style;

        $request = $this->doRequest('https://secure' . ($this->test ? '-test' : '') . '.dpay.pl/register', [
            'json' => $data
        ], 'POST');

        if(!$request->status || $request->error == true) throw new TransferException('DPay error: '.$request->msg);

        $this->payment_generated = true;
        $this->payment_id = $request->transactionId;
        $this->payment_url = $request->msg;
        return true;
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