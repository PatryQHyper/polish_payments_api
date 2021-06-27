<?php

namespace PatryQHyper\Payments\DirectBilling;

use PatryQHyper\Payments\Exceptions\DirectBillingException;
use PatryQHyper\Payments\WebClient;

class SimPayDirectBilling extends WebClient
{
    private int $service_id;
    private string $api_key;

    private bool $is_generated = false;
    private ?string $payment_id;
    private ?string $payment_url;

    public function __construct(int $service_id, string $api_key)
    {
        $this->service_id = $service_id;
        $this->api_key = $api_key;
    }

    public function generate(string $control, float $amount, ?string $complete_url=NULL, ?string $failure_url=NULL, ?int $provider=NULL)
    {
        $data['serviceId'] = $this->service_id;
        $data['control'] = $control;
        $data['amount'] = $amount;
        if(!is_null($complete_url)) $data['complete'] = $complete_url;
        if(!is_null($failure_url)) $data['failure'] = $failure_url;
        if(!is_null($provider)) $data['failure'] = $provider;

        $data['sign'] = hash('sha256', $this->service_id.$amount.$control.$this->api_key);

        $result = $this->doRequest('https://simpay.pl/db/api', [
            'form_params'=>$data
        ], 'POST', false);

        if($result->status == 'success')
        {
            $this->is_generated = true;
            $this->payment_id = $result->name;
            $this->payment_url = $result->link;

            return true;
        }

        throw new DirectBillingException('simpay returned error '.$result->message);
    }

    public function getTransactionId(): ?string
    {
        if (!$this->is_generated) {
            throw new DirectBillingException('payment is not generated');
        }

        return $this->payment_id;
    }

    public function getTransactionUrl(): ?string
    {
        if (!$this->is_generated) {
            throw new DirectBillingException('payment is not generated');
        }

        return $this->payment_url;
    }
}