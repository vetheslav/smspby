<?php

declare(strict_types=1);

namespace Vetheslav\SmspBy\Response;

use Vetheslav\SmspBy\Model\ApiError;
use Vetheslav\SmspBy\ValueObject\SmsCostItem;

final class SmsBulkCostResponse extends AbstractResponse
{
    /**
     * Creates a bulk SMS cost response wrapper.
     * @param SmsCostItem[] $messages
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
     * Builds a bulk SMS cost response from the API payload.
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

            $messages[] = new SmsCostItem(
                (string) $item['msisdn'],
                isset($item['price']) ? (float) $item['price'] : null,
                isset($item['parts']) ? (int) $item['parts'] : null,
                isset($item['amount']) ? (float) $item['amount'] : null,
            );
        }

        return new self($success, $error, $data, $messages);
    }

    /**
     * Returns the list of cost calculation items for each SMS.
     * @return SmsCostItem[]
     */
    public function messages(): array
    {
        return $this->messages;
    }
}
