<?php

namespace PatryQHyper\Payments\Online\Methods;

use PatryQHyper\Payments\Exceptions\PaymentException;
use PatryQHyper\Payments\Helpers\ArrayHelper;
use PatryQHyper\Payments\Online\PaymentAbstract;
use PatryQHyper\Payments\Online\PaymentGeneratedResponse;

class SimPayPayment extends PaymentAbstract
{
    private float $amount;
    private ?string $currency = null;
    private ?string $description = null;
    private ?string $control = null;
    private ?string $returnSuccess = null;
    private ?string $returnFail = null;
    private ?string $customerName = null;
    private ?string $customerEmail = null;
    private ?string $customerIp = null;
    private ?string $antiFraudUserAgent = null;
    private ?string $antiFraudSteamId = null;
    private ?string $antiFraudMcUsername = null;
    private ?string $antiFraudMcId = null;
    private ?string $billingName = null;
    private ?string $billingSurname = null;
    private ?string $billingStreet = null;
    private ?string $billingBuilding = null;
    private ?string $billingFlat = null;
    private ?string $billingCity = null;
    private ?string $billingRegion = null;
    private ?string $billingPostalCode = null;
    private ?string $billingCountry = null;
    private ?string $billingCompany = null;
    private ?string $shippingName = null;
    private ?string $shippingSurname = null;
    private ?string $shippingStreet = null;
    private ?string $shippingBuilding = null;
    private ?string $shippingFlat = null;
    private ?string $shippingCity = null;
    private ?string $shippingRegion = null;
    private ?string $shippingPostalCode = null;
    private ?string $shippingCountry = null;
    private ?string $shippingCompany = null;
    private ?array $cart = null;
    private ?string $directChannel;
    private ?array $channels = null;
    private ?bool $channelTypeBlik = null;
    private ?bool $channelTypeTransfer = null;
    private ?bool $channelTypeCards = null;
    private ?bool $channelTypeEWallets = null;
    private ?bool $channelTypePayPal = null;
    private ?bool $channelTypePaySafe = null;
    private ?string $referer = null;

    public function __construct(private string $bearerToken, private string $serviceId, private string $serviceIpnHash)
    {
    }

    public function setAmount(float $amount): SimPayPayment
    {
        $this->amount = $amount;
        return $this;
    }

    public function setCurrency(?string $currency): SimPayPayment
    {
        $this->currency = $currency;
        return $this;
    }

    public function setDescription(?string $description): SimPayPayment
    {
        $this->description = $description;
        return $this;
    }

    public function setControl(?string $control): SimPayPayment
    {
        $this->control = $control;
        return $this;
    }

    public function setReturnSuccess(?string $returnSuccess): SimPayPayment
    {
        $this->returnSuccess = $returnSuccess;
        return $this;
    }

    public function setReturnFail(?string $returnFail): SimPayPayment
    {
        $this->returnFail = $returnFail;
        return $this;
    }

    public function setCustomerName(?string $customerName): SimPayPayment
    {
        $this->customerName = $customerName;
        return $this;
    }

    public function setCustomerEmail(?string $customerEmail): SimPayPayment
    {
        $this->customerEmail = $customerEmail;
        return $this;
    }

    public function setCustomerIp(?string $customerIp): SimPayPayment
    {
        $this->customerIp = $customerIp;
        return $this;
    }

    public function setAntiFraudUserAgent(?string $antiFraudUserAgent): SimPayPayment
    {
        $this->antiFraudUserAgent = $antiFraudUserAgent;
        return $this;
    }

    public function setAntiFraudSteamId(?string $antiFraudSteamId): SimPayPayment
    {
        $this->antiFraudSteamId = $antiFraudSteamId;
        return $this;
    }

    public function setAntiFraudMcUsername(?string $antiFraudMcUsername): SimPayPayment
    {
        $this->antiFraudMcUsername = $antiFraudMcUsername;
        return $this;
    }

    public function setAntiFraudMcId(?string $antiFraudMcId): SimPayPayment
    {
        $this->antiFraudMcId = $antiFraudMcId;
        return $this;
    }

    public function setBillingName(?string $billingName): SimPayPayment
    {
        $this->billingName = $billingName;
        return $this;
    }

    public function setBillingSurname(?string $billingSurname): SimPayPayment
    {
        $this->billingSurname = $billingSurname;
        return $this;
    }

    public function setBillingStreet(?string $billingStreet): SimPayPayment
    {
        $this->billingStreet = $billingStreet;
        return $this;
    }

    public function setBillingBuilding(?string $billingBuilding): SimPayPayment
    {
        $this->billingBuilding = $billingBuilding;
        return $this;
    }

    public function setBillingFlat(?string $billingFlat): SimPayPayment
    {
        $this->billingFlat = $billingFlat;
        return $this;
    }

    public function setBillingCity(?string $billingCity): SimPayPayment
    {
        $this->billingCity = $billingCity;
        return $this;
    }

    public function setBillingRegion(?string $billingRegion): SimPayPayment
    {
        $this->billingRegion = $billingRegion;
        return $this;
    }

    public function setBillingPostalCode(?string $billingPostalCode): SimPayPayment
    {
        $this->billingPostalCode = $billingPostalCode;
        return $this;
    }

    public function setBillingCountry(?string $billingCountry): SimPayPayment
    {
        $this->billingCountry = $billingCountry;
        return $this;
    }

    public function setBillingCompany(?string $billingCompany): SimPayPayment
    {
        $this->billingCompany = $billingCompany;
        return $this;
    }

    public function setShippingName(?string $shippingName): SimPayPayment
    {
        $this->shippingName = $shippingName;
        return $this;
    }

    public function setShippingSurname(?string $shippingSurname): SimPayPayment
    {
        $this->shippingSurname = $shippingSurname;
        return $this;
    }

    public function setShippingStreet(?string $shippingStreet): SimPayPayment
    {
        $this->shippingStreet = $shippingStreet;
        return $this;
    }

    public function setShippingBuilding(?string $shippingBuilding): SimPayPayment
    {
        $this->shippingBuilding = $shippingBuilding;
        return $this;
    }

    public function setShippingFlat(?string $shippingFlat): SimPayPayment
    {
        $this->shippingFlat = $shippingFlat;
        return $this;
    }

    public function setShippingCity(?string $shippingCity): SimPayPayment
    {
        $this->shippingCity = $shippingCity;
        return $this;
    }

    public function setShippingRegion(?string $shippingRegion): SimPayPayment
    {
        $this->shippingRegion = $shippingRegion;
        return $this;
    }

    public function setShippingPostalCode(?string $shippingPostalCode): SimPayPayment
    {
        $this->shippingPostalCode = $shippingPostalCode;
        return $this;
    }

    public function setShippingCountry(?string $shippingCountry): SimPayPayment
    {
        $this->shippingCountry = $shippingCountry;
        return $this;
    }

    public function setShippingCompany(?string $shippingCompany): SimPayPayment
    {
        $this->shippingCompany = $shippingCompany;
        return $this;
    }

    public function setCart(?array $cart): SimPayPayment
    {
        $this->cart = $cart;
        return $this;
    }

    public function setDirectChannel(?string $directChannel): SimPayPayment
    {
        $this->directChannel = $directChannel;
        return $this;
    }

    public function setChannels(?array $channels): SimPayPayment
    {
        $this->channels = $channels;
        return $this;
    }

    public function setChannelTypeBlik(?bool $channelTypeBlik): SimPayPayment
    {
        $this->channelTypeBlik = $channelTypeBlik;
        return $this;
    }

    public function setChannelTypeTransfer(?bool $channelTypeTransfer): SimPayPayment
    {
        $this->channelTypeTransfer = $channelTypeTransfer;
        return $this;
    }

    public function setChannelTypeCards(?bool $channelTypeCards): SimPayPayment
    {
        $this->channelTypeCards = $channelTypeCards;
        return $this;
    }

    public function setChannelTypeEWallets(?bool $channelTypeEWallets): SimPayPayment
    {
        $this->channelTypeEWallets = $channelTypeEWallets;
        return $this;
    }

    public function setChannelTypePayPal(?bool $channelTypePayPal): SimPayPayment
    {
        $this->channelTypePayPal = $channelTypePayPal;
        return $this;
    }

    public function setChannelTypePaySafe(?bool $channelTypePaySafe): SimPayPayment
    {
        $this->channelTypePaySafe = $channelTypePaySafe;
        return $this;
    }

    public function setReferer(?string $referer): SimPayPayment
    {
        $this->referer = $referer;
        return $this;
    }

    /**
     * @throws PaymentException
     */
    public function generatePayment(): PaymentGeneratedResponse
    {
        $array['amount'] = $this->amount;

        if(!empty($this->currency)) {
            $array['currency'] = $this->currency;
        }

        if(!empty($this->description)) {
            $array['description'] = $this->description;
        }

        if(!empty($this->control)) {
            $array['control'] = $this->control;
        }

        if(!empty($this->customerName)) {
            $array['customer']['name'] = $this->customerName;
        }

        if(!empty($this->customerEmail)) {
            $array['customer']['email'] = $this->customerEmail;
        }

        if(!empty($this->customerEmail)) {
            $array['customer']['ip'] = $this->customerIp;
        }

        if(!empty($this->antiFraudUserAgent)) {
            $array['antifraud']['useragent'] = $this->antiFraudUserAgent;
        }

        if(!empty($this->antiFraudSteamId)) {
            $array['antifraud']['steamid'] = $this->antiFraudSteamId;
        }

        if(!empty($this->antiFraudMcUsername)) {
            $array['antifraud']['mcusername'] = $this->antiFraudMcUsername;
        }

        if(!empty($this->antiFraudMcId)) {
            $array['antifraud']['mcid'] = $this->antiFraudMcId;
        }

        if(!empty($this->billingName)) {
            $array['billing']['name'] = $this->billingName;
        }

        if(!empty($this->billingSurname)) {
            $array['billing']['surname'] = $this->billingSurname;
        }

        if(!empty($this->billingStreet)) {
            $array['billing']['street'] = $this->billingStreet;
        }

        if(!empty($this->billingBuilding)) {
            $array['billing']['building'] = $this->billingBuilding;
        }

        if(!empty($this->billingFlat)) {
            $array['billing']['flat'] = $this->billingFlat;
        }

        if(!empty($this->billingCity)) {
            $array['billing']['city'] = $this->billingCity;
        }

        if(!empty($this->billingRegion)) {
            $array['billing']['region'] = $this->billingRegion;
        }

        if(!empty($this->billingPostalCode)) {
            $array['billing']['postalCode'] = $this->billingPostalCode;
        }

        if(!empty($this->billingCountry)) {
            $array['billing']['country'] = $this->billingCountry;
        }

        if(!empty($this->billingCompany)) {
            $array['billing']['company'] = $this->billingCompany;
        }

        if(!empty($this->shippingName)) {
            $array['shipping']['name'] = $this->shippingName;
        }

        if(!empty($this->shippingSurname)) {
            $array['shipping']['surname'] = $this->shippingSurname;
        }

        if(!empty($this->shippingStreet)) {
            $array['shipping']['street'] = $this->shippingStreet;
        }

        if(!empty($this->shippingBuilding)) {
            $array['shipping']['building'] = $this->shippingBuilding;
        }

        if(!empty($this->shippingFlat)) {
            $array['shipping']['flat'] = $this->shippingFlat;
        }

        if(!empty($this->shippingCity)) {
            $array['shipping']['city'] = $this->shippingCity;
        }

        if(!empty($this->shippingRegion)) {
            $array['shipping']['region'] = $this->shippingRegion;
        }

        if(!empty($this->shippingPostalCode)) {
            $array['shipping']['postalCode'] = $this->shippingPostalCode;
        }

        if(!empty($this->shippingCountry)) {
            $array['shipping']['country'] = $this->shippingCountry;
        }

        if(!empty($this->shippingCompany)) {
            $array['shipping']['company'] = $this->shippingCompany;
        }

        if(is_array($this->cart) && count($this->cart) > 0) {
            $array['cart'] = $this->cart;
        }

        if (!empty($this->returnSuccess)) {
            $array['returns']['success'] = $this->returnSuccess;
        }

        if (!empty($this->returnFail)) {
            $array['returns']['failure'] = $this->returnFail;
        }

        if(!empty($this->directChannel)) {
            $array['directChannel'] = $this->directChannel;
        }

        if(is_array($this->channels) && count($this->channels) > 0) {
            $array['channels'] = $this->channels;
        }

        if(!is_null($this->channelTypeBlik)) {
            $array['channelTypes']['blik'] = $this->channelTypeBlik;
        }

        if(!is_null($this->channelTypeTransfer)) {
            $array['channelTypes']['transfer'] = $this->channelTypeTransfer;
        }

        if(!is_null($this->channelTypeCards)) {
            $array['channelTypes']['cards'] = $this->channelTypeCards;
        }

        if(!is_null($this->channelTypeEWallets)) {
            $array['channelTypes']['ewallets'] = $this->channelTypeEWallets;
        }

        if(!is_null($this->channelTypePayPal)) {
            $array['channelTypes']['paypal'] = $this->channelTypePayPal;
        }

        if(!is_null($this->channelTypePaySafe)) {
            $array['channelTypes']['paysafe'] = $this->channelTypePaySafe;
        }

        if(!empty($this->referer)) {
            $array['referer'] = $this->referer;
        }

        $request = $this->doRequest(sprintf('https://api.simpay.pl/payment/%s/transactions', $this->serviceId), [
            'json' => $array,
            'headers' => [
                'Authorization' => 'Bearer ' . $this->bearerToken,
            ],
        ], 'POST', false, false);

        if ($request->getStatusCode() !== 201) {
            throw new PaymentException(sprintf('SimPay error [%d]: %s', $request->getStatusCode(), $request->getBody()));
        }

        $json = json_decode($request->getBody());

        return new PaymentGeneratedResponse(
            $json->data->redirectUrl,
            $json->data->transactionId,
        );
    }

    public function generateIpnSignature(array $payload): string
    {
        unset($payload['signature']);

        $data = ArrayHelper::flatten($payload);
        $data[] = $this->serviceIpnHash;

        return hash('sha256', implode('|', $data));
    }
}