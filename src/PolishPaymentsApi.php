<?php

/**
 * Created with love by: Patryk Vizauer (wizjoner.dev)
 * Date: 21.03.2023 13:46
 */

namespace PatryQHyper\Payments;

use PatryQHyper\Payments\Providers\Provider;

class PolishPaymentsApi
{
    public const VERSION = '4.0.0';

    public function online(Provider $provider): Provider
    {
        return $provider;
    }
}