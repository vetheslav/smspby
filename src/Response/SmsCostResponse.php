<?php

declare(strict_types=1);

namespace Vetheslav\SmspBy\Response;

use Vetheslav\SmspBy\Model\ApiError;

final class SmsCostResponse extends AbstractResponse
{
    /**
     * Creates an SMS cost response wrapper.
     */
    public function __construct(
        bool $success,
        ?ApiError $apiError,
        array $raw,
        private readonly ?float $pricePerPart,
        private readonly ?int $parts,
        private readonly ?float $amount,
    ) {
        parent::__construct($success, $apiError, $raw);
    }

    /**
     * Builds an SMS cost response from the API payload.
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
            isset($data['parts']) ? (int) $data['parts'] : null,
            isset($data['amount']) ? (float) $data['amount'] : null,
        );
    }

    /**
     * Returns the price per SMS part for the cost calculation.
     */
    public function pricePerPart(): ?float
    {
        return $this->pricePerPart;
    }

    /**
     * Returns the number of SMS parts for the cost calculation.
     */
    public function parts(): ?int
    {
        return $this->parts;
    }

    /**
     * Returns the total cost for the SMS cost calculation.
     */
    public function amount(): ?float
    {
        return $this->amount;
    }
}
