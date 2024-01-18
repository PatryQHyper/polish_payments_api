<?php

namespace PatryQHyper\Payments\Providers\Miscellaneous\Dpay;

class TestEnvironment extends AbstractEnvironment
{
    public function getApiUrl(): string
    {
        return 'https://secure-test.dpay.pl';
    }

    public function getPanelUrl(): string
    {
        return 'https://panel.digitalpayments.pl';
    }
}