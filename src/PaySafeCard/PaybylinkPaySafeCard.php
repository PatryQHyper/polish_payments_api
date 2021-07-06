<?php

namespace PatryQHyper\Payments\PaySafeCard;

use PatryQHyper\Payments\Exceptions\PaySafeCardException;
use PatryQHyper\Payments\WebClient;

class PaybylinkPaySafeCard extends WebClient
{
    private int $user_id;
    private int $shop_id;
    private string $pin;

    private bool $is_generated = false;
    private ?string $pid;

    public function __construct(int $user_id, int $shop_id, string $pin)
    {
        $this->user_id = $user_id;
        $this->shop_id = $shop_id;
        $this->pin = $pin;
    }

    public function generate(float $price, string $return_ok, string $return_fail, string $notify_url, string $control, ?string $description=NULL)
    {
        $data = [
            'userid'=>$this->user_id,
            'shopid'=>$this->shop_id,
            'amount'=>$price,
            'return_ok'=>$return_ok,
            'return_fail'=>$return_fail,
            'url'=>$notify_url,
            'control'=>$control,
            'get_pid'=>true
        ];
        if(!is_null($description)) $data['description'] = $description;

        $data['hash'] = hash('sha256', $this->user_id.$this->pin.$price);

        $response = $this->doRequest('https://paybylink.pl/api/psc/', [
            'form_params'=>$data
        ], 'POST', false, false);

        $body = json_decode($response->getBody());
        if(!$body->status)
        {
            throw new PaySafeCardException('paybylink returned error '.$body->message);
        }

        $this->is_generated = true;
        $this->pid = $body->pid;
        return true;
    }

    public function getPid()
    {
        if(!$this->is_generated)
        {
            throw new PaySafeCardException('payment is not generated');
        }

        return $this->pid;
    }

    public function getPaymentUrl()
    {
        if(!$this->is_generated)
        {
            throw new PaySafeCardException('payment is not generated');
        }

        return 'https://paybylink.pl/pay/'.$this->pid;
    }
}