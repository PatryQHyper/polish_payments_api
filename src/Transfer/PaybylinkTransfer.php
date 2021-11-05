<?php

namespace PatryQHyper\Payments\Transfer;

use PatryQHyper\Payments\Exceptions\TransferException;
use PatryQHyper\Payments\WebClient;

class PaybylinkTransfer extends WebClient
{
    private int $shop_id;
    private string $hash;

    private bool $is_generated = false;
    private ?string $transaction_id;
    private ?string $transaction_url;

    public function __construct(int $shop_id, string $hash)
    {
        $this->shop_id = $shop_id;
        $this->hash = $hash;
    }

    public function generate(
        float $price,
        ?string $control=NULL,
        ?string $description=NULL,
        ?string $email=NULL,
        ?string $notifyUrl=NULL,
        ?string $returnUrlSuccess=NULL,
        ?string $customFinishNote=NULL
    )
    {
        $params['shopId'] = $this->shop_id;
        $params['price'] = sprintf('%.2f', $price);
        if(!is_null($control)) $params['control'] = $control;
        if(!is_null($description)) $params['description'] = $description;
        if(!is_null($email)) $params['email'] = $email;
        if(!is_null($notifyUrl)) $params['notifyURL'] = $notifyUrl;
        if(!is_null($returnUrlSuccess)) $params['returnUrlSuccess'] = $returnUrlSuccess;
        if(!is_null($customFinishNote)) $params['customFinishNote'] = $customFinishNote;

        $params['signature'] = hash('sha256', $this->hash.'|'.implode('|', $params));

        $response = $this->doRequest('https://secure.pbl.pl/api/v1/transfer/generate', [
            'json'=>$params
        ], 'POST', false, false);
        if($response->getStatusCode() != 200)
        {
            throw new TransferException('paybylink returned error '. (object) $response->getBody());
        }

        $body = json_decode($response->getBody());

        $this->is_generated = true;
        $this->transaction_url = $body->url;
        $this->transaction_id = $body->transactionId;
        return true;
    }

    public function generateIpnHash(array $params)
    {
        return hash('sha256', $this->hash.'|'.$params['transactionId'].'|'.$params['control'].'|'.$params['email'].'|'.sprintf('%.2f', $params['amountPaid']).'|'.$params['notificationAttempt'].'|'.$params['paymentType'].'|'.$params['apiVersion']);
    }

    public function getTransactionId(): ?string
    {
        if (!$this->is_generated) {
            throw new TransferException('payment is not generated');
        }

        return $this->transaction_id;
    }

    public function getTransactionUrl(): ?string
    {
        if (!$this->is_generated) {
            throw new TransferException('payment is not generated');
        }

        return $this->transaction_url;
    }
}