<?php

namespace PatryQHyper\Payments\Providers\Miscellaneous\HotPay;

abstract class AbstractMethod
{
    abstract public function getUrl(): string;
}