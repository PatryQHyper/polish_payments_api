<?php

namespace PatryQHyper\Payments\Providers\Notifications;

class SimPayDirectBillingNotification extends Notification
{

    /**
     * @inheritDoc
     */
    public function handle()
    {
        $this->checkHttpMethod();

        if ($this->payload->signature != $this->generateSignature()) {
            return $this->failed('invalid signature', 400);
        }

        return $this->valid();
    }

    private function generateSignature(): string
    {
        if (isset($this->payload->id)) {
            $payload[] = $this->payload->id;
        }
        if (isset($this->payload->service_id)) {
            $payload[] = $this->payload->service_id;
        }
        if (isset($this->payload->status)) {
            $payload[] = $this->payload->status;
        }
        if (isset($this->payload->values->net)) {
            $payload[] = $this->payload->values->net;
        }
        if (isset($this->payload->values->gross)) {
            $payload[] = $this->payload->values->gross;
        }
        if (isset($this->payload->values->partner)) {
            $payload[] = $this->payload->values->partner;
        }
        if (isset($this->payload->returns->complete)) {
            $payload[] = $this->payload->returns->complete;
        }
        if (isset($this->payload->returns->failure)) {
            $payload[] = $this->payload->returns->failure;
        }
        if (isset($this->payload->control)) {
            $payload[] = $this->payload->control;
        }
        if (isset($this->payload->number_from)) {
            $payload[] = $this->payload->number_from;
        }
        if (isset($this->payload->provider)) {
            $payload[] = $this->payload->provider;
        }

        $payload[] = $this->config['serviceHash'];

        return hash('sha256', implode('|', $payload));
    }
}