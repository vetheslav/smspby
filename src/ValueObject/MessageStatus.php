<?php

declare(strict_types=1);

namespace Vetheslav\SmspBy\ValueObject;

final readonly class MessageStatus
{
    /**
     * Creates a delivery status wrapper for SMS/Viber.
     */
    public function __construct(
        private MessageChannel $messageChannel,
        private GatewayStatus $gatewayStatus,
    ) {
    }

    /**
     * Returns the delivery status enum (SMS or Viber), or null if unavailable.
     */
    public function code(): SmsMessageStatus|ViberMessageStatus|null
    {
        return match ($this->messageChannel) {
            MessageChannel::Sms => $this->gatewayStatus->mapToSmsMessageStatus(),
            MessageChannel::Viber => $this->gatewayStatus->mapToViberMessageStatus(),
        };
    }

    /**
     * Returns the human-readable status label from the API.
     */
    public function name(): ?string
    {
        return $this->gatewayStatus->name();
    }

    /**
     * Returns the raw gateway status payload wrapper.
     */
    public function gatewayStatus(): GatewayStatus
    {
        return $this->gatewayStatus;
    }

    /**
     * Returns true when the gateway reports the message was not found (code=false).
     */
    public function isNotFound(): bool
    {
        return $this->gatewayStatus->isNotFound();
    }

    /**
     * Creates a MessageStatus from a raw SMS status code.
     */
    public static function fromSmsCode(int|false|null $code, ?string $name): self
    {
        return new self(MessageChannel::Sms, new GatewayStatus($code, $name));
    }

    /**
     * Creates a MessageStatus from a raw Viber status code.
     */
    public static function fromViberCode(int|false|null $code, ?string $name): self
    {
        return new self(MessageChannel::Viber, new GatewayStatus($code, $name));
    }
}
