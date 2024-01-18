<?php

namespace PatryQHyper\Payments\Providers\Miscellaneous\CashBill;

class ProductionEnvironment extends AbstractEnvironment
{
    public function getUrl(): string
    {
        return 'https://pay.cashbill.pl/ws/rest';
    }
}