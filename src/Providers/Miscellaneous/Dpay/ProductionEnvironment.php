<?php

namespace PatryQHyper\Payments\Providers\Miscellaneous\Dpay;

class ProductionEnvironment extends AbstractEnvironment
{
    public function getApiUrl(): string
    {
        return 'https://secure.dpay.pl';
    }

    public function getPanelUrl(): string
    {
        return 'https://panel.dpay.pl';
    }
}