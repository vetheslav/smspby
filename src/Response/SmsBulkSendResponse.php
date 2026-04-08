<?php

declare(strict_types=1);

namespace Vetheslav\SmspBy\Response;

use Vetheslav\SmspBy\Model\ApiError;

final class SmsBulkSendResponse extends AbstractResponse
{
    /**
     * Creates a bulk SMS send response wrapper.
     * @param SmsSendResponse[] $messages
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
     * Builds a bulk SMS send response from the API payload.
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
            
            $item['status'] = true;
            $messages[] = SmsSendResponse::fromArray($item);
        }

        return new self($success, $error, $data, $messages);
    }

    /**
     * Returns the list of send results for each SMS as SmsSendResponse items.
     */
    public function messages(): array
    {
        return $this->messages;
    }
}
