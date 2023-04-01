<?php

/**
 * Created with love by: Patryk Vizauer (wizjoner.dev)
 * Date: 01.04.2023 18:31
 */

namespace PatryQHyper\Payments\Providers;

use PatryQHyper\Payments\Exceptions\GeneratePaymentException;
use PatryQHyper\Payments\Providers\Miscellaneous\HotPay\AbstractMethod;
use PatryQHyper\Payments\Providers\Miscellaneous\HotPay\TransferMethod;
use PatryQHyper\Payments\Providers\Notifications\HotPayNotification;
use PatryQHyper\Payments\Providers\Notifications\Notification;
use PatryQHyper\Payments\Providers\Setters\HotPaySetters;
use PatryQHyper\Payments\Responses\PaymentGeneratedResponse;

class HotPay extends HotPaySetters
{
    public function __construct(
        private string         $secret,
        private string         $notificationPassword,
        private AbstractMethod $method = new TransferMethod(),
    )
    {
    }

    /**
     * @inheritDoc
     */
    public function generatePayment(): PaymentGeneratedResponse
    {
        $payload = [
            'KWOTA' => $this->amount,
            'NAZWA_USLUGI' => $this->description,
            'ADRES_WWW' => $this->redirectUrl,
            'ID_ZAMOWIENIA' => $this->orderId,
            'SEKRET' => $this->secret,
        ];

        $payload['HASH'] = hash('sha256', $this->notificationPassword . ';' . implode(';', $payload));
        if (isset($this->email)) {
            $payload['EMAIL'] = $this->email;
        }
        if (isset($this->personalData)) {
            $payload['DANE_OSOBOWE'] = $this->personalData;
        }
        $payload['TYP'] = 'INIT';

        $request = $this->doRequest($this->method->getUrl(), [
            'form_params' => $payload,
        ], 'POST');

        $json = @json_decode($request->getBody());
        if (!$json) {
            throw new GeneratePaymentException('HotPay error: invalid notification password');
        }

        if (!$json->STATUS) {
            throw new GeneratePaymentException('HotPay error: ' . $json->WIADOMOSC);
        }

        return new PaymentGeneratedResponse($json->URL);
    }

    /**
     * @inheritDoc
     */
    public function handleNotification(object|array $payload): Notification
    {
        return (new HotPayNotification($payload, [
            'secret' => $this->secret,
            'notificationPassword' => $this->notificationPassword,
        ]))->handle();
    }
}