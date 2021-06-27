<?php

namespace PatryQHyper\Payments\Sms;

use PatryQHyper\Payments\Exceptions\SmsException;
use PatryQHyper\Payments\WebClient;

class HotPaySMS extends WebClient
{
    private string $secret;

    private bool $is_sent = false;
    private bool $is_used = false;

    public function __construct(string $secret)
    {
        $this->secret = $secret;
    }

    public function check($code): bool
    {
        $response = $this->doRequest('https://apiv2.hotpay.pl/v1/sms/sprawdz', [
            'sekret' => $this->secret,
            'kod_sms' => $code
        ]);

        $this->is_sent = true;

        if ($response->status == 'ERROR' && $response->tresc != 'BLEDNA TRESC SMS') {
            throw new SmsException('received error from hotpay ' . $response->tresc);
        } elseif ($response->status == 'SUKCESS') {
            if ((int)$response->aktywacja == 1) {
                return true;
            } else {
                $this->is_used = true;
                return false;
            }
        }

        return false;
    }

    public function checkIfUsed(): bool
    {
        if (!$this->is_sent) {
            throw new SmsException('payment is not sent');
        }

        return $this->is_used;
    }
}