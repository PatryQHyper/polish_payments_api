<?php

namespace PatryQHyper\Payments\Providers\Miscellaneous\CashBill;

abstract class AbstractEnvironment
{
    abstract public function getUrl(): string;
}