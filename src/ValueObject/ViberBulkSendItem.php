<?php

declare(strict_types=1);

namespace Vetheslav\SmspBy\ValueObject;

final readonly class ViberBulkSendItem
{
    /**
     * Creates a new instance.
     */
    public function __construct(
        private ?int $messageId,
        private ?float $price,
        private ?string $customId,
    ) {
    }

    /**
     * Returns the platform message ID assigned to the Viber message.
     */
    public function messageId(): ?int
    {
        return $this->messageId;
    }

    /**
     * Returns the cost of the Viber message.
     */
    public function price(): ?float
    {
        return $this->price;
    }

    /**
     * Returns the custom_id associated with the Viber message, if provided.
     */
    public function customId(): ?string
    {
        return $this->customId;
    }
}
