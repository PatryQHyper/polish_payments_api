<?php

namespace PatryQHyper\Payments\Providers\Notifications;

class HotPayNotification extends Notification
{
    /**
     * @inheritDoc
     */
    public function handle()
    {
        $this->checkHttpMethod();
        $this->checkParameters($this->parameters);

        if (!$this->payload->SECURE) {
            return $this->failed('notification not secured', 403);
        }

        if ($this->payload->HASH != $this->generateNotificationHash()) {
            return $this->failed('invalid HASH', 400);
        }

        if ($this->payload->SEKRET != $this->config['secret']) {
            return $this->failed('invalid secret', 400);
        }

        return $this->valid();
    }

    private function generateNotificationHash()
    {
        $data = [
            $this->config['notificationPassword'],
            $this->payload->KWOTA,
            $this->payload->ID_PLATNOSCI,
            $this->payload->ID_ZAMOWIENIA,
            $this->payload->STATUS,
            $this->payload->SECURE,
            $this->payload->SEKRET,
        ];

        return hash('sha256', implode(';', $data));
    }

    private array $parameters = [
        'KWOTA',
        'ID_PLATNOSCI',
        'ID_ZAMOWIENIA',
        'STATUS',
        'SEKRET',
        'SECURE',
        'HASH',
    ];
}