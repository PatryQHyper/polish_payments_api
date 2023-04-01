<?php

/**
 * Created with love by: Patryk Vizauer (wizjoner.dev)
 * Date: 01.04.2023 18:33
 */

namespace PatryQHyper\Payments\Providers\Miscellaneous\HotPay;

class PaySafeCardMethod extends AbstractMethod
{
    public function getUrl(): string
    {
        return 'https://psc.hotpay.pl';
    }
}