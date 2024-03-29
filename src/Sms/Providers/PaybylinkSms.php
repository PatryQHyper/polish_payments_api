<?php

/**
 * Created with love by: Patryk Vizauer (patryqhyper.pl)
 * Date: 08.06.2022 15:19
 * Using: PhpStorm
 */

namespace PatryQHyper\Payments\Sms\Providers;

use PatryQHyper\Payments\Exceptions\InvalidSmsCodeException;
use PatryQHyper\Payments\Exceptions\PaymentException;
use PatryQHyper\Payments\Exceptions\UsedSmsCodeException;
use PatryQHyper\Payments\Sms\SmsAbstract;

class PaybylinkSms extends SmsAbstract
{
    public function __construct(private int $userId, private int $serviceId)
    {
    }

    /**
     * @throws UsedSmsCodeException
     * @throws PaymentException
     * @throws InvalidSmsCodeException
     */
    public function check($code, int $number): bool
    {
        $request = $this->doRequest('https://paybylink.pl/api/v2/index.php', [
            'userid' => $this->userId,
            'serviceid' => $this->serviceId,
            'code' => $code,
            'number' => $number
        ]);

        if (isset($request->error)) {
            throw new PaymentException(sprintf('Paybylink error no %d: %s', $request->error->errorCode, $request->error->message));
        }

        if (!$request->connect && $request->data->errorCode != 1) {
            throw new PaymentException(sprintf('Paybylink error no %d: %s', $request->data->errorCode, $request->data->message));
        }

        if (isset($request->data->errorCode) && $request->data->errorCode == 1) {
            throw new InvalidSmsCodeException();
        }

        if ($request->data->status != 1) {
            throw new UsedSmsCodeException();
        }

        return true;
    }
}