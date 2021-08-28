<?php

namespace PatryQHyper\Payments\Transfer;

use PatryQHyper\Payments\Exceptions\TransferException;
use PatryQHyper\Payments\WebClient;

class HotPayApiTransfer extends WebClient
{
    private string $secret;
    private string $pass;

    private bool $payment_generated = false;
    private ?string $payment_url;

    public function __construct(string $secret, string $pass)
    {
        $this->secret = $secret;
        $this->pass = $pass;
    }

    public function generate(
        float   $price,
        string  $service_name,
        ?string $redirect_url = NULL,
        ?string $order_id = NULL,
        ?string $email = NULL,
        ?string $personal_data = NULL
    )
    {
        $params = [
            'KWOTA' => $price,
            'NAZWA_USLUGI' => $service_name,
            'ADRES_WWW' => $redirect_url,
            'ID_ZAMOWIENIA' => $order_id,
            'SEKRET' => $this->secret,
        ];

        $params['HASH'] = hash('sha256',
            $this->pass.';'.
            implode(';', $params)
        );

        $params['EMAIL'] = $email;
        $params['DANE_OSOBOWE'] = $personal_data;
        $params['TYP'] = 'INIT';

        $request = $this->doRequest('https://platnosc.hotpay.pl/', [
            'form_params'=>$params
        ], 'POST', false, false);

        $json = @json_decode($request->getBody());
        if(!$json) throw new TransferException('HotPay error: invalid notification password');

        if(!$json->STATUS) throw new TransferException('HotPay error: '.$json->WIADOMOSC);

        $this->payment_generated = true;
        $this->payment_url = $json->URL;

        return true;
    }

    public function getTransactionUrl(): ?string
    {
        if (!$this->payment_generated) {
            throw new TransferException('payment is not generated');
        }

        return $this->payment_url;
    }

    public function generateHash($post)
    {
        return hash('sha256', $this->pass . ';' . $post['KWOTA'] . ';' . $post['ID_PLATNOSCI'] . ';' . $post["ID_ZAMOWIENIA"] . ';' . $post['STATUS'] . ';' . $post['SECURE'] . ';' . $this->secret);
    }
}