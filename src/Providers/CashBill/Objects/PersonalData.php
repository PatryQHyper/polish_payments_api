<?php

namespace PatryQHyper\Payments\Providers\CashBill\Objects;

class PersonalData
{
    public ?string $firstName = null;
    public ?string $surname = null;
    public ?string $email = null;
    public ?string $country = null;
    public ?string $city = null;
    public ?string $postCode = null;
    public ?string $street = null;
    public ?string $house = null;
    public ?string $flat = null;
    public ?string $ip = null;

    public function __construct(public readonly array $data)
    {
        $this->firstName = $data['firstName'] ?? null;
        $this->surname = $data['surname'] ?? null;
        $this->email = $data['email'] ?? null;
        $this->country = $data['country'] ?? null;
        $this->city = $data['city'] ?? null;
        $this->postCode = $data['postCode'] ?? null;
        $this->street = $data['street'] ?? null;
        $this->house = $data['house'] ?? null;
        $this->flat = $data['flat'] ?? null;
        $this->ip = $data['ip'] ?? null;
    }
}