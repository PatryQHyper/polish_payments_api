<?php

/**
 * Created with love by: Patryk Vizauer (wizjoner.dev)
 * Date: 21.03.2023 14:02
 */

namespace PatryQHyper\Payments\Providers\Miscellaneous\CashBill;

abstract class AbstractEnvironment
{
    abstract public function getUrl(): string;
}