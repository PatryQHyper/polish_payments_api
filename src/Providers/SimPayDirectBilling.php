<?php

/**
 * Created with love by: Patryk Vizauer (wizjoner.dev)
 * Date: 01.04.2023 13:44
 */

namespace PatryQHyper\Payments\Providers;

use PatryQHyper\Payments\Exceptions\GeneratePaymentException;
use PatryQHyper\Payments\PolishPaymentsApi;
use PatryQHyper\Payments\Providers\Notifications\Notification;
use PatryQHyper\Payments\Providers\Notifications\SimPayDirectBillingNotification;
use PatryQHyper\Payments\Providers\Setters\SimPayDirectBillingSetters;
use PatryQHyper\Payments\Responses\PaymentGeneratedResponse;

class SimPayDirectBilling extends SimPayDirectBillingSetters
{
    public function __construct(
        private string $apiKey,
        private string $apiPassword,
        private string $serviceId,
        private string $serviceHash,
    )
    {
    }

    /**
     * @inheritDoc
     */
    public function generatePayment(): PaymentGeneratedResponse
    {
        $payload['amount'] = $this->amount;
        $payload['amountType'] = $this->amountType;
        if (isset($this->description)) {
            $payload['description'] = $this->description;
        }
        if (isset($this->control)) {
            $payload['control'] = $this->control;
        }
        if (isset($this->returnSuccess)) {
            $payload['returns']['success'] = $this->returnSuccess;
        }
        if (isset($this->returnFail)) {
            $payload['returns']['failure'] = $this->returnFail;
        }
        if (isset($this->phoneNumber)) {
            $payload['phoneNumber'] = $this->phoneNumber;
        }
        if (isset($this->steamId)) {
            $payload['steamid'] = $this->steamId;
        }

        $payload['signature'] = $this->generateInitSignature();


        $request = $this->doRequest(sprintf('https://api.simpay.pl/directbilling/%s/transactions', $this->serviceId), [
            'json' => $payload,
            'headers' => [
                'X-SIM-KEY' => $this->apiKey,
                'X-SIM-PASSWORD' => $this->apiPassword,
                'X-SIM-VERSION' => 'PolishPaymentsApi:' . PolishPaymentsApi::VERSION,
            ],
        ], 'POST');

        if ($request->getStatusCode() != 200) {
            throw new GeneratePaymentException(sprintf('Simpay error: %s', $request->getBody()));
        }

        $json = json_decode($request->getBody());

        return new PaymentGeneratedResponse(
            $json->data->redirectUrl,
            $json->data->transactionId,
        );
    }

    /**
     * @inheritDoc
     */
    public function handleNotification(object|array $payload): Notification
    {
        return (new SimPayDirectBillingNotification($payload, [
            'apiKey' => $this->apiKey,
            'apiPassword' => $this->apiPassword,
            'serviceId' => $this->serviceId,
            'serviceHash' => $this->serviceHash,
        ]))->handle();

    }

    private function generateInitSignature()
    {
        $data[] = $this->amount;
        $data[] = $this->amountType;
        if (isset($this->description)) {
            $data[] = $this->description;
        }

        if (isset($this->control)) {
            $data[] = $this->control;
        }
        if (isset($this->returnSuccess)) {
            $data[] = $this->returnSuccess;
        }
        if (isset($this->returnFail)) {
            $data[] = $this->returnFail;
        }
        if (isset($this->phoneNumber)) {
            $data[] = $this->phoneNumber;
        }
        if (isset($this->steamId)) {
            $data[] = $this->steamId;
        }

        $data[] = $this->serviceHash;

        return hash('sha256', implode('|', $data));
    }
}