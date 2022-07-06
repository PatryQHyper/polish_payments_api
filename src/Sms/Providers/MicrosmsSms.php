<?php

/**
 * Created with love by: Patryk Vizauer (patryqhyper.pl)
 * Date: 08.06.2022 15:35
 * Using: PhpStorm
 */

namespace PatryQHyper\Payments\Sms\Providers;

use PatryQHyper\Payments\Exceptions\InvalidSmsCodeException;
use PatryQHyper\Payments\Exceptions\PaymentException;
use PatryQHyper\Payments\Exceptions\UsedSmsCodeException;
use PatryQHyper\Payments\Sms\SmsAbstract;

class MicrosmsSms extends SmsAbstract
{
    private int $userId;
    private int $serviceId;

    public function __construct(int $userId, int $serviceId)
    {
        $this->userId = $userId;
        $this->serviceId = $serviceId;
    }

    public function check($code, int $number)
    {
        $request = $this->doRequest('https://microsms.pl/api/v2/index.php', [
            'userid' => $this->userId,
            'serviceid' => $this->serviceId,
            'code' => $code,
            'number' => $number
        ]);

        if(isset($request->error))
            throw new PaymentException(sprintf('Microsms error no %d: %s', $request->error->errorCode, $request->error->message));

        if (!$request->connect && $request->data->errorCode != 1)
            throw new PaymentException(sprintf('Microsms error no %d: %s', $request->data->errorCode, $request->data->message));

        if (isset($request->data->errorCode) && $request->data->errorCode == 1)
            throw new InvalidSmsCodeException();

        if ($request->data->status != 1)
            throw new UsedSmsCodeException();

        return true;
    }
}