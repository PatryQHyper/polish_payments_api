<?php

namespace PatryQHyper\Payments\Providers\CashBill;

enum Environment
{
    case PRODUCTION;
    case TEST;

    public function getUrl():string
    {
        return match ($this) {
            self::PRODUCTION => 'https://pay.cashbill.pl/ws/rest',
            self::TEST => 'https://pay.cashbill.pl/testws/rest',
        };
    }
}
