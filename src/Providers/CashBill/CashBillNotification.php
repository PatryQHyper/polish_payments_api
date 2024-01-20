<?php

namespace PatryQHyper\Payments\Providers\CashBill;

use PatryQHyper\Payments\Providers\Notification;

class CashBillNotification extends Notification
{
    public function handle(): Notification
    {
        $this->checkHttpMethod('GET');
        $this->checkParameters(['cmd', 'args', 'sign']);

        if ($this->payload->cmd !== 'transactionStatusChanged') {
            return $this->failed('invalid cmd', 400);
        }

        if ($this->payload->sign !== md5($this->payload->cmd . $this->payload->args . $this->config['shopKey'])) {
            return $this->failed('invalid sign', 400);
        }

        return $this->valid();
    }
}