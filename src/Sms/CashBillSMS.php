<?php

namespace PatryQHyper\Payments\Sms;

use PatryQHyper\Payments\Exceptions\SmsException;
use PatryQHyper\Payments\WebClient;

class CashBillSMS extends WebClient
{
    private string $token;

    private bool $is_sent = false;
    private bool $is_used = false;
    private ?int $sms_number = NULL;

    public function __construct(string $token)
    {
        $this->token = $token;
    }

    public function check($code): bool
    {
        $response = $this->doRequest('https://sms.cashbill.pl/code/' . $this->token . '/' . $code, [], 'GET', false, false);

        $this->is_sent = true;

        $body = json_decode($response->getBody());

        if (isset($body->error)) {
            return false;
        }

        if ($body->active) {
            if ($body->activeFrom == NULL && $response->getStatusCode() == 200) {
                $this->sms_number = $body->number;
                return true;
            }
        }
        $this->is_used = true;
        return false;
    }

    public function checkIfUsed(): bool
    {
        if (!$this->is_sent) {
            throw new SmsException('payment is not sent');
        }

        return $this->is_used;
    }

    public function getNumber(): ?int
    {
        return $this->sms_number;
    }
}