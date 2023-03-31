<?php

/**
 * Created with love by: Patryk Vizauer (wizjoner.dev)
 * Date: 31.03.2023 22:48
 */

namespace PatryQHyper\Payments\Providers\Notifications;

class CashBillNotification extends Notification
{
    public function handle(): Notification
    {
        $this->checkHttpMethod('GET');
        $this->checkParameters(['cmd', 'args', 'sign']);

        if ($this->payload->cmd != 'transactionStatusChanged') {
            return $this->failed('invalid cmd', 400);
        }

        if ($this->payload->sign != md5($this->payload->cmd . $this->payload->args . $this->config['shopKey'])) {
            return $this->failed('invalid sign', 400);
        }

        return $this->valid();
    }

    public function responseOk()
    {
        http_response_code(200);
        header('Content-Type: plain/text');
        exit('OK');
    }
}