<?php

declare(strict_types=1);

namespace Vetheslav\SmspBy\ValueObject;

final readonly class ViberCostMessage
{
    /**
     * Creates a typed Viber cost request with validation.
     */
    public function __construct(
        private string           $msisdn,
        private ViberMessageType $viberMessageType,
    ) {
        if ($this->msisdn === '') {
            throw new \InvalidArgumentException('msisdn must be a non-empty string.');
        }
    }

    /**
     * Builds the payload for cost/viber and costBulk/viber.
     * @return array<string|int>
     */
    public function toArray(): array
    {
        return [
            'msisdn' => $this->msisdn,
            'type' => $this->viberMessageType->value,
        ];
    }
}
