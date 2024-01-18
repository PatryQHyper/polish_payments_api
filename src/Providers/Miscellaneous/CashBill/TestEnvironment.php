<?php

namespace PatryQHyper\Payments\Providers\Miscellaneous\CashBill;

class TestEnvironment extends AbstractEnvironment
{
    public function getUrl(): string
    {
        return 'https://pay.cashbill.pl/testws/rest';
    }
}