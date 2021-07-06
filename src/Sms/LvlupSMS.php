<?php

namespace PatryQHyper\Payments\Sms;

use PatryQHyper\Payments\WebClient;

class LvlupSMS extends WebClient
{
    private int $user_id;

    public function __construct(int $user_id)
    {
        $this->user_id = $user_id;
    }

    public function check($code, int $number, $description=''): bool
    {
        $response = $this->doRequest('https://lvlup.pro/api/checksms', [
            'id' => $this->user_id,
            'code' => $code,
            'number' => $number,
            'desc' => $description,
        ]);

        if($response->valid) return true;
        return false;
    }
}