<?php

namespace PatryQHyper\Payments\Responses;

use PatryQHyper\Payments\Providers\CashBill\Objects\Amount;
use PatryQHyper\Payments\Providers\CashBill\Objects\Details;
use PatryQHyper\Payments\Providers\CashBill\Objects\PersonalData;

class CashBillTransactionDetails
{
    public ?string $id;
    public ?string $paymentChannel;
    public Amount $amount;
    public Amount $requestedAmount;
    public ?string $title;
    public ?string $description;
    public PersonalData $personalData;
    public ?string $additionalData;
    public ?string $status;
    public Details $details;

    public function __construct(
        public readonly array $data,
    )
    {
        $this->id = $data['id'];
        $this->paymentChannel = $data['paymentChannel'];
        $this->amount = new Amount($data['amount']);
        $this->requestedAmount = new Amount($data['requestedAmount']);
        $this->title = $data['title'];
        $this->description = $data['description'];
        $this->personalData = new PersonalData($data['personalData']);
        $this->additionalData = $data['additionalData'];
        $this->status = $data['status'];
        $this->details = new Details($data['details'] ?? []);
    }
}