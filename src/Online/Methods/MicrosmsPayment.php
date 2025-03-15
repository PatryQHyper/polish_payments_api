<?php

namespace PatryQHyper\Payments\Online\Methods;

use PatryQHyper\Payments\Online\PaymentAbstract;
use PatryQHyper\Payments\Online\PaymentGeneratedResponse;

/**
 * @deprecated MicroSMS does not exist anymore. This class will be removed in future release.
 */
class MicrosmsPayment extends PaymentAbstract
{
    private int $shopId;
    private int $userId;
    private string $hash;
    private bool $useMd5;

    public const HASH_SHA256 = false;
    public const HASH_MD5 = true;

    private float $amount;
    private string $control;
    private string $returnUrlc;
    private string $returnUrl;
    private string $description;

    public function __construct(int $shopId, int $userId, string $hash, bool $useMd5 = false)
    {
        trigger_error('Class ' . __CLASS__ . ' is deprecated. MicroSMS does not exist anymore. This class will be removed in future release.' , E_USER_DEPRECATED);

        $this->shopId = $shopId;
        $this->userId = $userId;
        $this->hash = $hash;
        $this->useMd5 = $useMd5;
    }

    public function setAmount(float $amount): MicrosmsPayment
    {
        $this->amount = $amount;
        return $this;
    }

    public function setControl(string $control): MicrosmsPayment
    {
        $this->control = $control;
        return $this;
    }

    public function setReturnUrlc(string $returnUrlc): MicrosmsPayment
    {
        $this->returnUrlc = $returnUrlc;
        return $this;
    }

    public function setReturnUrl(string $returnUrl): MicrosmsPayment
    {
        $this->returnUrl = $returnUrl;
        return $this;
    }

    public function setDescription(string $description): MicrosmsPayment
    {
        $this->description = $description;
        return $this;
    }

    public function generatePayment(): PaymentGeneratedResponse
    {
        $data['shopid'] = $this->shopId;
        $data['amount'] = $this->amount;
        if (isset($this->control)) $data['control'] = $this->control;
        if (isset($this->returnUrlc)) $data['return_urlc'] = $this->returnUrlc;
        if (isset($this->returnUrl)) $data['return_url'] = $this->returnUrl;
        if (isset($this->description)) $data['description'] = $this->description;
        $toHash = $this->shopId . $this->hash . $this->amount;
        $data['signature'] = $this->useMd5 ? md5($toHash) : hash('sha256', $toHash);

        return new PaymentGeneratedResponse(
            'https://microsms.pl/api/bankTransfer/?' . http_build_query($data)
        );
    }
}