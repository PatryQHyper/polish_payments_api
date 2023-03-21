<?php

/**
 * Created with love by: Patryk Vizauer (wizjoner.dev)
 * Date: 21.03.2023 14:04
 */

namespace PatryQHyper\Payments\Providers\Miscellaneous\CashBill;

class TestEnvironment extends AbstractEnvironment
{
    public function getUrl(): string
    {
        return 'https://pay.cashbill.pl/testws/rest';
    }
}