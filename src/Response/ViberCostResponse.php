<?php

declare(strict_types=1);

namespace Vetheslav\SmspBy\Response;

use Vetheslav\SmspBy\Model\ApiError;

final class ViberCostResponse extends AbstractResponse
{
    /**
     * Creates a Viber cost response wrapper.
     */
    public function __construct(
        bool $success,
        ?ApiError $apiError,
        array $raw,
        private readonly ?float $price,
    ) {
        parent::__construct($success, $apiError, $raw);
    }

    /**
     * Builds a Viber cost response from the API payload.
     */
    public static function fromArray(array $data): self
    {
        $success = ($data['status'] ?? true) !== false;
        $error = $success ? null : ApiError::fromMixed($data['error'] ?? null);

        return new self(
            $success,
            $error,
            $data,
            isset($data['price']) ? (float) $data['price'] : null,
        );
    }

    /**
     * Returns the cost of the Viber message for the cost calculation.
     */
    public function price(): ?float
    {
        return $this->price;
    }
}
