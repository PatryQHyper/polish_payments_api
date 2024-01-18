<?php

namespace PatryQHyper\Payments\Providers\Miscellaneous\HotPay;

class PaySafeCardMethod extends AbstractMethod
{
    public function getUrl(): string
    {
        return 'https://psc.hotpay.pl';
    }
}