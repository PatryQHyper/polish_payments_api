<?php

/**
 * Created with love by: Patryk Vizauer (patryqhyper.pl)
 * Date: 20.05.2022 22:00
 * Using: PhpStorm
 */

namespace PatryQHyper\Payments\Online\Methods;

use PatryQHyper\Payments\Online\PaymentAbstract;
use PatryQHyper\Payments\Online\PaymentGeneratedResponse;

class DotPayPayment extends PaymentAbstract
{
    private int $shopId;
    private string $pin;
    private bool $useSandbox;

    public const ENVIRONMENT_SANDBOX = true;
    public const ENVIRONMENT_PRODUCTION = false;

    private float $amount;
    private string $currency = 'PLN';
    private string $description;
    private string $channel;
    private int $chLock;
    private int $ignoreLastPaymentChannel;
    private string $channelGroups;
    private string $redirectUrl;
    private string $type;
    private string $buttonText;
    private int $byLaw;
    private int $personalData;
    private string $urlc;
    private string $expirationDate;
    private string $control;
    private string $firstname;
    private string $lastname;
    private string $email;
    private string $street;
    private string $streetN1;
    private string $streetN2;
    private string $state;
    private string $addr3;
    private string $city;
    private string $postCode;
    private string $phone;
    private string $country;
    private string $lang;
    private string $customer;
    private string $deliveryAddress;
    private string $pInfo;
    private string $pEmail;
    private string $blikCode;
    private string $gpToken;
    private string $apToken;

    public function __construct(int $shopId, string $pin, bool $useSandbox)
    {
        $this->shopId = $shopId;
        $this->pin = $pin;
        $this->useSandbox = $useSandbox;
    }

    public function setAmount(float $amount): DotPayPayment
    {
        $this->amount = $amount;
        return $this;
    }

    public function setCurrency(string $currency): DotPayPayment
    {
        $this->currency = $currency;
        return $this;
    }

    public function setDescription(string $description): DotPayPayment
    {
        $this->description = $description;
        return $this;
    }

    public function setChannel(string $channel): DotPayPayment
    {
        $this->channel = $channel;
        return $this;
    }

    public function setChLock(int $chLock): DotPayPayment
    {
        $this->chLock = $chLock;
        return $this;
    }

    public function setIgnoreLastPaymentChannel(int $ignoreLastPaymentChannel): DotPayPayment
    {
        $this->ignoreLastPaymentChannel = $ignoreLastPaymentChannel;
        return $this;
    }

    public function setChannelGroups(string $channelGroups): DotPayPayment
    {
        $this->channelGroups = $channelGroups;
        return $this;
    }

    public function setRedirectUrl(string $redirectUrl): DotPayPayment
    {
        $this->redirectUrl = $redirectUrl;
        return $this;
    }

    public function setType(string $type): DotPayPayment
    {
        $this->type = $type;
        return $this;
    }

    public function setButtonText(string $buttonText): DotPayPayment
    {
        $this->buttonText = $buttonText;
        return $this;
    }

    public function setByLaw(int $byLaw): DotPayPayment
    {
        $this->byLaw = $byLaw;
        return $this;
    }

    public function setPersonalData(int $personalData): DotPayPayment
    {
        $this->personalData = $personalData;
        return $this;
    }

    public function setUrlc(string $urlc): DotPayPayment
    {
        $this->urlc = $urlc;
        return $this;
    }

    public function setExpirationDate(string $expirationDate): DotPayPayment
    {
        $this->expirationDate = $expirationDate;
        return $this;
    }

    public function setControl(string $control): DotPayPayment
    {
        $this->control = $control;
        return $this;
    }

    public function setFirstname(string $firstname): DotPayPayment
    {
        $this->firstname = $firstname;
        return $this;
    }

    public function setLastname(string $lastname): DotPayPayment
    {
        $this->lastname = $lastname;
        return $this;
    }

    public function setEmail(string $email): DotPayPayment
    {
        $this->email = $email;
        return $this;
    }

    public function setStreet(string $street): DotPayPayment
    {
        $this->street = $street;
        return $this;
    }

    public function setStreetN1(string $streetN1): DotPayPayment
    {
        $this->streetN1 = $streetN1;
        return $this;
    }

    public function setStreetN2(string $streetN2): DotPayPayment
    {
        $this->streetN2 = $streetN2;
        return $this;
    }

    public function setState(string $state): DotPayPayment
    {
        $this->state = $state;
        return $this;
    }

    public function setAddr3(string $addr3): DotPayPayment
    {
        $this->addr3 = $addr3;
        return $this;
    }

    public function setCity(string $city): DotPayPayment
    {
        $this->city = $city;
        return $this;
    }

    public function setPostCode(string $postCode): DotPayPayment
    {
        $this->postCode = $postCode;
        return $this;
    }

    public function setPhone(string $phone): DotPayPayment
    {
        $this->phone = $phone;
        return $this;
    }

    public function setCountry(string $country): DotPayPayment
    {
        $this->country = $country;
        return $this;
    }

    public function setLang(string $lang): DotPayPayment
    {
        $this->lang = $lang;
        return $this;
    }

    public function setCustomer(string $customer): DotPayPayment
    {
        $this->customer = $customer;
        return $this;
    }

    public function setDeliveryAddress(string $deliveryAddress): DotPayPayment
    {
        $this->deliveryAddress = $deliveryAddress;
        return $this;
    }

    public function setPInfo(string $pInfo): DotPayPayment
    {
        $this->pInfo = $pInfo;
        return $this;
    }

    public function setPEmail(string $pEmail): DotPayPayment
    {
        $this->pEmail = $pEmail;
        return $this;
    }

    public function setBlikCode(string $blikCode): DotPayPayment
    {
        $this->blikCode = $blikCode;
        return $this;
    }

    public function setGpToken(string $gpToken): DotPayPayment
    {
        $this->gpToken = $gpToken;
        return $this;
    }

    public function setApToken(string $apToken): DotPayPayment
    {
        $this->apToken = $apToken;
        return $this;
    }

    public function generatePayment(): PaymentGeneratedResponse
    {
        $array['api_version'] = 'next';
        $array['id'] = $this->shopId;
        $array['amount'] = $this->amount;
        $array['currency'] = $this->currency;
        $array['description'] = $this->description;
        if (isset($this->channel)) $array['channel'] = $this->channel;
        if (isset($this->chLock)) $array['ch_lock'] = $this->chLock;
        if (isset($this->ignoreLastPaymentChannel)) $array['ignore_last_payment_channel'] = $this->ignoreLastPaymentChannel;
        if (isset($this->channelGroups)) $array['channel_groups'] = $this->channelGroups;
        if (isset($this->redirectUrl)) $array['url'] = $this->redirectUrl;
        if (isset($this->type)) $array['type'] = $this->type;
        if (isset($this->buttonText)) $array['buttontext'] = $this->buttonText;
        if (isset($this->byLaw)) $array['bylaw'] = $this->byLaw;
        if (isset($this->personalData)) $array['personal_data'] = $this->personalData;
        if (isset($this->urlc)) $array['urlc'] = $this->urlc;
        if (isset($this->expirationDate)) $array['expirationDate'] = $this->expirationDate;
        if (isset($this->control)) $array['control'] = $this->control;
        if (isset($this->firstname)) $array['firstname'] = $this->firstname;
        if (isset($this->lastname)) $array['lastname'] = $this->lastname;
        if (isset($this->email)) $array['email'] = $this->email;
        if (isset($this->street)) $array['street'] = $this->street;
        if (isset($this->streetN1)) $array['street_n1'] = $this->streetN1;
        if (isset($this->streetN2)) $array['street_n2'] = $this->streetN2;
        if (isset($this->state)) $array['state'] = $this->state;
        if (isset($this->addr3)) $array['addr3'] = $this->addr3;
        if (isset($this->city)) $array['city'] = $this->city;
        if (isset($this->postCode)) $array['postcode'] = $this->postCode;
        if (isset($this->phone)) $array['phone'] = $this->phone;
        if (isset($this->country)) $array['country'] = $this->country;
        if (isset($this->lang)) $array['lang'] = $this->lang;
        if (isset($this->customer)) $array['customer'] = $this->customer;
        if (isset($this->deliveryAddress)) $array['deladdr'] = $this->deliveryAddress;
        if (isset($this->pInfo)) $array['p_info'] = $this->pInfo;
        if (isset($this->pEmail)) $array['p_email'] = $this->pEmail;
        if (isset($this->blikCode)) $array['blik_code'] = $this->blikCode;
        if (isset($this->gpToken)) $array['gp_token'] = $this->gpToken;
        if (isset($this->apToken)) $array['ap_token'] = $this->apToken;

        foreach ($array as $name => $value) {
            $array[$name] = (string)$value;
        }
        $array['chk'] = $this->generateChk($array);

        return new PaymentGeneratedResponse(
            sprintf('https://dproxy.przelewy24.pl/%s/?%s', ($this->useSandbox ? 'test_payment' : 't2'), http_build_query($array))
        );
    }

    public function generateUrlcSignature(array $post): bool|string
    {
        return hash('sha256',
            $this->pin .
            ($post['id'] ?? '') .
            ($post['operation_number'] ?? '') .
            ($post['operation_type'] ?? '') .
            ($post['operation_status'] ?? '') .
            ($post['operation_amount'] ?? '') .
            ($post['operation_currency'] ?? '') .
            ($post['operation_withdrawal_amount'] ?? '') .
            ($post['operation_commission_amount'] ?? '') .
            ($post['is_completed'] ?? '') .
            ($post['operation_original_amount'] ?? '') .
            ($post['operation_original_currency'] ?? '') .
            ($post['operation_datetime'] ?? '') .
            ($post['operation_related_number'] ?? '') .
            ($post['control'] ?? '') .
            ($post['description'] ?? '') .
            ($post['email'] ?? '') .
            ($post['p_info'] ?? '') .
            ($post['p_email'] ?? '') .
            ($post['credit_card_issuer_identification_number'] ?? '') .
            ($post['credit_card_masked_number'] ?? '') .
            ($post['credit_card_expiration_year'] ?? '') .
            ($post['credit_card_expiration_month'] ?? '') .
            ($post['credit_card_brand_codename'] ?? '') .
            ($post['credit_card_brand_code'] ?? '') .
            ($post['credit_card_unique_identifier'] ?? '') .
            ($post['credit_card_id'] ?? '') .
            ($post['channel'] ?? '') .
            ($post['channel_country'] ?? '') .
            ($post['geoip_country'] ?? '') .
            ($post['payer_bank_account_name'] ?? '') .
            ($post['payer_bank_account'] ?? '') .
            ($post['payer_transfer_title'] ?? '') .
            ($post['blik_voucher_pin'] ?? '') .
            ($post['blik_voucher_amount'] ?? '') .
            ($post['blik_voucher_amount_used'] ?? '') .
            ($post['channel_reference_id'] ?? '') .
            ($post['operation_seller_code'] ?? '')
        );
    }

    private function generateChk(array $array): string
    {
        ksort($array);
        $array['paramsList'] = implode(';', array_keys($array));
        ksort($array);
        return hash_hmac('sha256', json_encode($array, JSON_UNESCAPED_SLASHES), $this->pin, false);
    }
}
