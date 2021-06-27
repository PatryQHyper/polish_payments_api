<?php

namespace PatryQHyper\Payments\Sms;

use PatryQHyper\Payments\Exceptions\SmsException;
use PatryQHyper\Payments\WebClient;

class MicrosmsSMS extends WebClient
{
    private int $user_id;
    private int $service_id;

    private bool $is_sent = false;
    private bool $is_used = false;

    public function __construct(int $user_id, int $service_id)
    {
        $this->user_id = $user_id;
        $this->service_id = $service_id;
    }

    public function check(int $number, $code): bool
    {
        $response = $this->doRequest('https://microsms.pl/api/v2/index.php', [
            'userid' => $this->user_id,
            'serviceid' => $this->service_id,
            'code' => $code,
            'number' => $number
        ]);

        if(isset($response->error))
        {
            throw new SmsException('Microsms returned error '.$response->error->message, $response->error->errorCode);
        }

        $this->is_sent = true;

        if(isset($response->connect) && $response->connect)
        {
            if($response->data->status == 1)
            {
                return true;
            }
            else {
                $this->is_used = true;
                return false;
            }
        }
        return false;
    }

    public function checkIfUsed(): bool
    {
        if(!$this->is_sent)
        {
            throw new SmsException('payment is not sent');
        }

        return $this->is_used;
    }
}