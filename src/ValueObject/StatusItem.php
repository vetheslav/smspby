<?php

declare(strict_types=1);

namespace Vetheslav\SmspBy\ValueObject;

final readonly class StatusItem
{
    /**
     * Creates a status item for bulk status responses.
     */
    public function __construct(
        private ?int $messageId,
        private ?string $customId,
        private MessageStatus $messageStatus,
    ) {
    }

    /**
     * Returns the platform message ID for this status item, if available.
     */
    public function messageId(): ?int
    {
        return $this->messageId;
    }

    /**
     * Returns the custom_id for this status item, if provided.
     */
    public function customId(): ?string
    {
        return $this->customId;
    }

    /**
     * Returns the parsed delivery status for this item.
     */
    public function status(): MessageStatus
    {
        return $this->messageStatus;
    }
}
