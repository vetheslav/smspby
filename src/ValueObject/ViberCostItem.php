<?php

declare(strict_types=1);

namespace Vetheslav\SmspBy\ValueObject;

final readonly class ViberCostItem
{
    /**
     * Creates a cost item for a Viber message.
     */
    public function __construct(
        private string $msisdn,
        private ?float $price,
    ) {
    }

    /**
     * Returns the recipient MSISDN for this cost item.
     */
    public function msisdn(): string
    {
        return $this->msisdn;
    }

    /**
     * Returns the cost for this Viber cost item.
     */
    public function price(): ?float
    {
        return $this->price;
    }
}
