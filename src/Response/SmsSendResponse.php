<?php

declare(strict_types=1);

namespace Vetheslav\SmspBy\Response;

use Vetheslav\SmspBy\Model\ApiError;

final class SmsSendResponse extends AbstractResponse
{
    /**
     * Creates an SMS send response wrapper.
     */
    public function __construct(
        bool $success,
        ?ApiError $apiError,
        array $raw,
        private readonly ?int $messageId,
        private readonly ?float $pricePerPart,
        private readonly ?int $parts,
        private readonly ?float $amount,
        private readonly ?string $customId,
    ) {
        parent::__construct($success, $apiError, $raw);
    }

    /**
     * Builds an SMS send response from the API payload.
     */
    public static function fromArray(array $data): self
    {
        $success = ($data['status'] ?? true) !== false;
        $error = $success ? null : ApiError::fromMixed($data['error'] ?? null);

        return new self(
            $success,
            $error,
            $data,
            isset($data['message_id']) ? (int) $data['message_id'] : null,
            isset($data['price']) ? (float) $data['price'] : null,
            isset($data['parts']) ? (int) $data['parts'] : null,
            isset($data['amount']) ? (float) $data['amount'] : null,
            isset($data['custom_id']) ? (string) $data['custom_id'] : null,
        );
    }

    /**
     * Returns the platform message ID assigned to the SMS.
     */
    public function messageId(): ?int
    {
        return $this->messageId;
    }

    /**
     * Returns the price per SMS part reported by the API.
     */
    public function pricePerPart(): ?float
    {
        return $this->pricePerPart;
    }

    /**
     * Returns the number of SMS parts.
     */
    public function parts(): ?int
    {
        return $this->parts;
    }

    /**
     * Returns the total cost of the SMS.
     */
    public function amount(): ?float
    {
        return $this->amount;
    }

    /**
     * Returns the custom_id associated with the SMS, if provided.
     */
    public function customId(): ?string
    {
        return $this->customId;
    }
}
