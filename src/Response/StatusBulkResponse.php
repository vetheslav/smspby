<?php

declare(strict_types=1);

namespace Vetheslav\SmspBy\Response;

use Vetheslav\SmspBy\Model\ApiError;
use Vetheslav\SmspBy\ValueObject\MessageStatus;
use Vetheslav\SmspBy\ValueObject\StatusItem;
use Vetheslav\SmspBy\ValueObject\MessageChannel;

final class StatusBulkResponse extends AbstractResponse
{
    /**
     * Creates a bulk status response wrapper.
     * @param StatusItem[] $items
     */
    public function __construct(
        bool $success,
        ?ApiError $apiError,
        array $raw,
        private readonly array $items,
    ) {
        parent::__construct($success, $apiError, $raw);
    }

    /**
     * Builds a bulk status response from an API payload using SMS status mapping by default.
     */
    public static function fromArray(array $data): self
    {
        return self::fromSmsArray($data);
    }

    /**
     * Builds a bulk status response interpreting status codes as SMS statuses.
     */
    public static function fromSmsArray(array $data): self
    {
        return self::fromArrayForChannel($data, MessageChannel::Sms);
    }

    /**
     * Builds a bulk status response interpreting status codes as Viber statuses.
     */
    public static function fromViberArray(array $data): self
    {
        return self::fromArrayForChannel($data, MessageChannel::Viber);
    }

    private static function fromArrayForChannel(array $data, MessageChannel $messageChannel): self
    {
        $success = ($data['status'] ?? true) !== false;
        $error = $success ? null : ApiError::fromMixed($data['error'] ?? null);

        $items = [];
        $messagesStatus = $data['messages_status'] ?? [];
        if (\is_array($messagesStatus) && array_is_list($messagesStatus) === false && isset($messagesStatus['code'])) {
            $messagesStatus = [$messagesStatus];
        }

        foreach ($messagesStatus as $messageStatus) {
            if (!\is_array($messageStatus)) {
                continue;
            }

            $messageId = null;
            if (isset($messageStatus['message_id']) && $messageStatus['message_id'] !== false) {
                $messageId = (int) $messageStatus['message_id'];
            }

            $customId = isset($messageStatus['custom_id']) ? (string) $messageStatus['custom_id'] : null;
            $name = isset($messageStatus['name']) ? (string) $messageStatus['name'] : null;
            $code = $messageStatus['code'] ?? null;

            $status = match ($messageChannel) {
                MessageChannel::Viber => MessageStatus::fromViberCode($code, $name),
                MessageChannel::Sms => MessageStatus::fromSmsCode($code, $name),
            };

            $items[] = new StatusItem($messageId, $customId, $status);
        }

        return new self($success, $error, $data, $items);
    }

    /**
     * Returns the list of parsed status entries.
     * @return StatusItem[]
     */
    public function items(): array
    {
        return $this->items;
    }
}
