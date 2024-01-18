<?php

namespace PatryQHyper\Payments\Providers\Miscellaneous\HotPay;

class TransferMethod extends AbstractMethod
{
    public function getUrl(): string
    {
        return 'https://platnosc.hotpay.pl';
    }
}