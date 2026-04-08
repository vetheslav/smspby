<?php

declare(strict_types=1);

namespace Vetheslav\SmspBy\Response;

use Vetheslav\SmspBy\Model\ApiError;
use Vetheslav\SmspBy\ValueObject\MessageStatus;
use Vetheslav\SmspBy\ValueObject\MessageChannel;

final class StatusResponse extends AbstractResponse
{
    /**
     * Creates a status response wrapper.
     */
    public function __construct(
        bool $success,
        ?ApiError $apiError,
        array $raw,
        private readonly MessageStatus $messageStatus,
    ) {
        parent::__construct($success, $apiError, $raw);
    }

    /**
     * Builds a status response from an API payload using SMS status mapping by default.
     */
    public static function fromArray(array $data): self
    {
        return self::fromSmsArray($data);
    }

    /**
     * Builds a status response interpreting status codes as SMS statuses.
     */
    public static function fromSmsArray(array $data): self
    {
        return self::fromArrayForChannel($data, MessageChannel::Sms);
    }

    /**
     * Builds a status response interpreting status codes as Viber statuses.
     */
    public static function fromViberArray(array $data): self
    {
        return self::fromArrayForChannel($data, MessageChannel::Viber);
    }

    private static function fromArrayForChannel(array $data, MessageChannel $messageChannel): self
    {
        $success = ($data['status'] ?? true) !== false;
        $error = $success ? null : ApiError::fromMixed($data['error'] ?? null);

        $statusData = \is_array($data['message_status'] ?? null) ? $data['message_status'] : [];
        $code = $statusData['code'] ?? null;
        $name = isset($statusData['name']) ? (string) $statusData['name'] : null;

        $messageStatus = match ($messageChannel) {
            MessageChannel::Viber => MessageStatus::fromViberCode($code, $name),
            MessageChannel::Sms => MessageStatus::fromSmsCode($code, $name),
        };

        return new self($success, $error, $data, $messageStatus);
    }

    /**
     * Returns the parsed delivery status information.
     */
    public function messageStatus(): MessageStatus
    {
        return $this->messageStatus;
    }
}
