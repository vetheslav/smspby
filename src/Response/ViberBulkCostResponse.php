<?php

declare(strict_types=1);

namespace Vetheslav\SmspBy\Response;

use Vetheslav\SmspBy\Model\ApiError;
use Vetheslav\SmspBy\ValueObject\ViberCostItem;

final class ViberBulkCostResponse extends AbstractResponse
{
    /**
     * Creates a bulk Viber cost response wrapper.
     * @param ViberCostItem[] $messages
     */
    public function __construct(
        bool $success,
        ?ApiError $apiError,
        array $raw,
        private readonly array $messages,
    ) {
        parent::__construct($success, $apiError, $raw);
    }

    /**
     * Builds a bulk Viber cost response from the API payload.
     */
    public static function fromArray(array $data): self
    {
        $success = ($data['status'] ?? true) !== false;
        $error = $success ? null : ApiError::fromMixed($data['error'] ?? null);

        $messages = [];
        foreach (($data['messages'] ?? []) as $item) {
            if (!\is_array($item)) {
                continue;
            }

            if (!isset($item['msisdn'])) {
                continue;
            }

            $messages[] = new ViberCostItem(
                (string) $item['msisdn'],
                isset($item['price']) ? (float) $item['price'] : null,
            );
        }

        return new self($success, $error, $data, $messages);
    }

    /**
     * Returns the list of cost calculation items for each Viber message.
     * @return ViberCostItem[]
     */
    public function messages(): array
    {
        return $this->messages;
    }
}
