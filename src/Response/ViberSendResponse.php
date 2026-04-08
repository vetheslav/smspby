<?php

declare(strict_types=1);

namespace Vetheslav\SmspBy\Response;

use Vetheslav\SmspBy\Model\ApiError;

final class ViberSendResponse extends AbstractResponse
{
    /**
     * Creates a Viber send response wrapper.
     */
    public function __construct(
        bool $success,
        ?ApiError $apiError,
        array $raw,
        private readonly ?int $messageId,
        private readonly ?float $price,
        private readonly ?string $customId,
    ) {
        parent::__construct($success, $apiError, $raw);
    }

    /**
     * Builds a Viber send response from the API payload.
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
            isset($data['custom_id']) ? (string) $data['custom_id'] : null,
        );
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
