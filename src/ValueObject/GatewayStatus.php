<?php

declare(strict_types=1);

namespace Vetheslav\SmspBy\ValueObject;

final readonly class GatewayStatus
{
    /**
     * Creates a gateway status wrapper with the raw API code and name.
     */
    public function __construct(
        private int|false|null $code,
        private ?string $name,
    ) {
    }

    /**
     * Returns the raw status code as received from the gateway.
     */
    public function rawCode(): int|false|null
    {
        return $this->code;
    }

    /**
     * Returns the human-readable status label from the API.
     */
    public function name(): ?string
    {
        return $this->name;
    }

    /**
     * Returns true when the gateway reports the message was not found (code=false).
     */
    public function isNotFound(): bool
    {
        return $this->code === false;
    }

    /**
     * Maps the raw code to an SMS status enum when possible.
     */
    public function mapToSmsMessageStatus(): ?SmsMessageStatus
    {
        if ($this->code === null || $this->code === false) {
            return null;
        }

        return SmsMessageStatus::tryFrom($this->code);
    }

    /**
     * Maps the raw code to a Viber status enum when possible.
     */
    public function mapToViberMessageStatus(): ?ViberMessageStatus
    {
        if ($this->code === null || $this->code === false) {
            return null;
        }

        return ViberMessageStatus::tryFrom($this->code);
    }
}
