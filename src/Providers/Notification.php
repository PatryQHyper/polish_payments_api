<?php

namespace PatryQHyper\Payments\Providers;

use PatryQHyper\Payments\Exceptions\NotificationException;

abstract class Notification
{
    protected object $payload;

    protected array $server;

    protected array $config = [];

    protected bool $isValid = false;

    protected ?string $error = null;
    protected int $errorCode = 0;

    public function __construct(array|object|null $payload, array $providerConfig = [])
    {
        $this->server = $_SERVER;
        $this->config = $providerConfig;
        if (!is_null($payload)) {
            $this->setPayload($payload);
        }
    }

    public function setPayload(array|object $payload): self
    {
        if (is_array($payload)) {
            $this->payload = (object)$payload;
            return $this;
        }

        $this->payload = $payload;
        return $this;
    }

    /**
     * @throws NotificationException
     */
    abstract public function handle();

    public function responseOk() {
        http_response_code(200);
        header('Content-Type: plain/text');
        exit('OK');
    }

    public function isValid(): bool
    {
        return $this->isValid;
    }

    public function getError(): string|null
    {
        return $this->error;
    }

    public function getErrorCode(): int|null
    {
        return $this->errorCode;
    }

    protected function failed(string $error, int $errorCode): self
    {
        $this->error = $error;
        $this->errorCode = $errorCode;
        return $this;
    }

    protected function valid(): self
    {
        $this->isValid = true;
        return $this;
    }

    protected function checkParameters(array $parameters = []): void
    {
        if (!count((array)$this->payload) && count($parameters)) {
            throw new NotificationException('invalid payload', 422);
        }

        foreach ((array)$this->payload as $name => $value) {
            if (!in_array($name, $parameters)) {
                throw new NotificationException('invalid payload', 422);
            }
        }
    }

    protected function checkHttpMethod(string $method = 'POST'): void
    {
        if (strtoupper($_SERVER['REQUEST_METHOD']) != strtoupper($method)) {
            throw new NotificationException('method not allowed', 405);
        }
    }
}