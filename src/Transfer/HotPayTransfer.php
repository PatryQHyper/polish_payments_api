<?php

namespace PatryQHyper\Payments\Transfer;

use PatryQHyper\Payments\WebClient;

class HotPayTransfer extends WebClient
{
    private string $secret;
    private string $pass;

    private string $redirect_url = 'https://platnosc.hotpay.pl/';
    private array $params = [];

    public function __construct(string $secret, string $pass)
    {
        $this->secret = $secret;
        $this->pass = $pass;
    }

    public function generate(float $price, string $service_name, ?string $redirect_url=NULL, ?string $order_id=NULL, ?string $email=NULL, ?string $personal_data=NULL, ?string $payment_method=NULL)
    {
        $this->params = [
            'SEKRET'=>$this->secret,
            'KWOTA'=>$price,
            'NAZWA_USLUGI'=>$service_name,
            'ADRES_WWW'=>$redirect_url,
            'ID_ZAMOWIENIA'=>$order_id,
            'EMAIL'=>$email,
            'DANE_OSOBOWE'=>$personal_data,
            'TYP_PLATNOSCI'=>$payment_method,
        ];

        return true;
    }

    public function getRedirectUrl(bool $add_parameters=false)
    {
        return $this->redirect_url.($add_parameters ? ('?'.http_build_query($this->params)) : '');
    }

    public function getParameters()
    {
        return $this->params;
    }

    public function generateHash($post)
    {
        return hash('sha256', $this->pass.';'.$post['KWOTA'].';'.$post['ID_PLATNOSCI'].';'.$post["ID_ZAMOWIENIA"].';'.$post['STATUS'].';'.$this->secret);
    }
}