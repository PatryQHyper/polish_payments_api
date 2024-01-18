<?php

namespace PatryQHyper\Payments\Providers\Miscellaneous\Dpay;

abstract class AbstractEnvironment
{
    abstract public function getApiUrl(): string;

    abstract public function getPanelUrl(): string;
}