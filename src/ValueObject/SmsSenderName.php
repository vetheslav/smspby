<?php

declare(strict_types=1);

namespace Vetheslav\SmspBy\ValueObject;

final readonly class SmsSenderName
{
    /**
     * Creates an SMS sender name value object.
     */
    public function __construct(
        private string $sender,
        private bool $isDefault,
    ) {
    }

    /**
     * Returns the SMS sender name string approved for the account.
     */
    public function sender(): string
    {
        return $this->sender;
    }

    /**
     * Returns whether this SMS sender is marked as default.
     */
    public function isDefault(): bool
    {
        return $this->isDefault;
    }
}
