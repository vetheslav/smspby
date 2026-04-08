<?php

declare(strict_types=1);

namespace Vetheslav\SmspBy\ValueObject;

final readonly class SmsCostMessage
{
    /**
     * Creates a typed SMS cost request with validation.
     */
    public function __construct(
        private string $msisdn,
        private string $text,
    ) {
        if ($this->msisdn === '') {
            throw new \InvalidArgumentException('msisdn must be a non-empty string.');
        }
        
        if ($this->text === '') {
            throw new \InvalidArgumentException('text must be a non-empty string.');
        }
    }

    /**
     * Builds the payload for cost/sms and costBulk/sms.
     */
    public function toArray(): array
    {
        return [
            'msisdn' => $this->msisdn,
            'text' => $this->text,
        ];
    }
}
