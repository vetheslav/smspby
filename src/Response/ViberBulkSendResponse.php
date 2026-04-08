<?php

declare(strict_types=1);

namespace Vetheslav\SmspBy\Response;

use Vetheslav\SmspBy\Model\ApiError;
use Vetheslav\SmspBy\ValueObject\ViberBulkSendItem;

final class ViberBulkSendResponse extends AbstractResponse
{
    /**
     * Creates a bulk Viber send response wrapper.
     * @param ViberBulkSendItem[] $messages
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
     * Builds a bulk Viber send response from the API payload.
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
            
            $messages[] = new ViberBulkSendItem(
                isset($item['message_id']) ? (int) $item['message_id'] : null,
                isset($item['price']) ? (float) $item['price'] : null,
                isset($item['custom_id']) ? (string) $item['custom_id'] : null,
            );
        }

        return new self($success, $error, $data, $messages);
    }

    /**
     * Returns the list of send results for each Viber message.
     * @return ViberBulkSendItem[]
     */
    public function messages(): array
    {
        return $this->messages;
    }
}
