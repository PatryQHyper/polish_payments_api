<?php

namespace PatryQHyper\Payments\Transfer;

class DotPayTransfer
{
    private int $shop_id;
    private string $pin;

    private string $dotpay_url = 'https://ssl.dotpay.pl';
    private array $params = [];

    public function __construct(int $shop_id, string $pin, bool $use_sandbox = false)
    {
        $this->shop_id = $shop_id;
        $this->pin = $pin;
        $this->dotpay_url .= ($use_sandbox ? '/test_payment/' : '/t2/');
    }

    public function generate(
        float $amount,
        string $description,
        ?string $url = NULL,
        ?string $urlc = NULL,
        ?string $control = NULL,
        ?string $channel_groups = NULL,
        ?int $ignore_last_payment_channel = NULL,
        ?string $currency = 'PLN',
        ?int $channel = NULL,
        ?int $ch_lock = NULL,
        ?int $type = 0,
        ?string $button_text = NULL,
        ?int $bylaw = NULL,
        ?int $personal_data = NULL,
        ?string $expiration_date = NULL,
        ?string $firstname = NULL,
        ?string $surname = NULL,
        ?string $email = NULL,
        ?string $street = NULL,
        ?string $street_n1 = NULL,
        ?string $street_n2 = NULL,
        ?string $state = NULL,
        ?string $addr3 = NULL,
        ?string $city = NULL,
        ?string $postcode = NULL,
        ?string $phone = NULL,
        ?string $country = NULL,
        ?string $p_info = NULL,
        ?string $p_email = NULL,
        ?string $language = 'pl',
        ?string $customer = NULL,
        ?string $deladdr = NULL,
        ?string $blik_code = NULL,
        ?string $gp_token = NULL,
        ?string $ap_token = NULL
    )
    {
        $params['api_version'] = 'next';
        $params['id'] = $this->shop_id;
        $params['amount'] = sprintf('%.2f', $amount);
        $params['currency'] = $currency;
        $params['description'] = $description;
        $params['lang'] = $language;
        if (!is_null($channel)) $params['channel'] = $channel;
        if (!is_null($ch_lock)) $params['ch_lock'] = $ch_lock;
        if (!is_null($ignore_last_payment_channel)) $params['ignore_last_payment_channel'] = $ignore_last_payment_channel;
        if (!is_null($channel_groups)) $params['channel_groups'] = $channel_groups;
        if (!is_null($url)) $params['url'] = $url;
        if (!is_null($type)) $params['type'] = $type;
        if (!is_null($button_text)) $params['buttontext'] = $button_text;
        if (!is_null($bylaw)) $params['bylaw'] = $bylaw;
        if (!is_null($personal_data)) $params['personal_data'] = $personal_data;
        if (!is_null($urlc)) $params['urlc'] = $urlc;
        if (!is_null($expiration_date)) $params['expiration_date'] = $expiration_date;
        if (!is_null($control)) $params['control'] = $control;
        if (!is_null($firstname)) $params['firstname'] = $firstname;
        if (!is_null($surname)) $params['lastname'] = $surname;
        if (!is_null($email)) $params['email'] = $email;
        if (!is_null($blik_code)) $params['blik_code'] = $blik_code;
        if (!is_null($street)) $params['street'] = $street;
        if (!is_null($street_n1)) $params['street_n1'] = $street_n1;
        if (!is_null($street_n2)) $params['street_n2'] = $street_n2;
        if (!is_null($state)) $params['state'] = $state;
        if (!is_null($addr3)) $params['addr3'] = $addr3;
        if (!is_null($city)) $params['city'] = $city;
        if (!is_null($postcode)) $params['postcode'] = $postcode;
        if (!is_null($phone)) $params['phone'] = $phone;
        if (!is_null($country)) $params['country'] = $country;
        if (!is_null($customer)) $params['customer'] = $customer;
        if (!is_null($deladdr)) $params['deladdr'] = $deladdr;
        if (!is_null($p_info)) $params['p_info'] = $p_info;
        if (!is_null($p_email)) $params['p_email'] = $p_email;
        if (!is_null($gp_token)) $params['gp_token'] = $gp_token;
        if (!is_null($ap_token)) $params['ap_token'] = $ap_token;

        foreach ($params as $name => $value) {
            $params[$name] = (string)$value;
        }

        $params['chk'] = $this->generateChk($params);

        $this->params = $params;

        return true;
    }

    private function generateChk($params)
    {
        ksort($params);

        $params['paramsList'] = implode(';', array_keys($params));

        ksort($params);

        return hash_hmac('sha256', json_encode($params, JSON_UNESCAPED_SLASHES), $this->pin, false);
    }

    public function generateUrlcSignature(array $data)
    {
        return hash('sha256', @(
            $this->pin
            . $this->shop_id
            . $data['operation_number'] ?? ''
            . $data['operation_type'] ?? ''
            . $data['operation_status'] ?? ''
            . $data['operation_amount'] ?? ''
            . $data['operation_currency'] ?? ''
            . $data['operation_withdrawal_amount'] ?? ''
            . $data['operation_commission_amount'] ?? ''
            . $data['is_completed'] ?? ''
            . $data['operation_original_amount'] ?? ''
            . $data['operation_original_currency'] ?? ''
            . $data['operation_datetime'] ?? ''
            . $data['operation_related_number'] ?? ''
            . $data['control'] ?? ''
            . $data['description'] ?? ''
            . $data['email'] ?? ''
            . $data['p_info'] ?? ''
            . $data['p_email'] ?? ''
            . $data['credit_card_issuer_identification_number'] ?? ''
            . $data['credit_card_masked_number'] ?? ''
            . $data['credit_card_expiration_year'] ?? ''
            . $data['credit_card_expiration_month'] ?? ''
            . $data['credit_card_brand_codename'] ?? ''
            . $data['credit_card_brand_code'] ?? ''
            . $data['credit_card_unique_identifier'] ?? ''
            . $data['credit_card_id'] ?? ''
            . $data['channel'] ?? ''
            . $data['channel_country'] ?? ''
            . $data['geoip_country'] ?? ''
            . $data['payer_bank_account_name'] ?? ''
            . $data['payer_bank_account'] ?? ''
            . $data['payer_transfer_title'] ?? ''
            . $data['blik_voucher_pin'] ?? ''
            . $data['blik_voucher_amount'] ?? ''
            . $data['blik_voucher_amount_used'] ?? ''
            . $data['channel_reference_id'] ?? ''
            . $data['operation_seller_code'] ?? ''
        ));
    }

    public function getRedirectUrl(bool $add_parameters = false)
    {
        return $this->dotpay_url . ($add_parameters ? ('?' . http_build_query($this->params)) : '');
    }

    public function getParameters()
    {
        return $this->params;
    }

}