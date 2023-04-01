<?php

/**
 * Created with love by: Patryk Vizauer (wizjoner.dev)
 * Date: 01.04.2023 18:32
 */

namespace PatryQHyper\Payments\Providers\Miscellaneous\HotPay;

class TransferMethod extends AbstractMethod
{
    public function getUrl(): string
    {
        return 'https://platnosc.hotpay.pl';
    }
}