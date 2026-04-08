<?php

declare(strict_types=1);

namespace Vetheslav\SmspBy\Api;

final class ApiHelpers
{
    /**
     * Validates that a batch list is non-empty and does not exceed the API limit.
     * @param array<int, mixed> $items
     */
    public static function assertBatchSize(array $items, int $limit = 500): void
    {
        if ($items === []) {
            throw new \InvalidArgumentException('Batch list must not be empty.');
        }
        
        if (count($items) > $limit) {
            throw new \InvalidArgumentException('Batch list exceeds the maximum size of '.$limit.'.');
        }
    }

    /**
     * Validates and joins a list of IDs into a comma-separated string for API requests.
     * @param array<int, string|int> $ids
     */
    public static function joinIds(array $ids): string
    {
        self::assertBatchSize($ids);

        $clean = [];
        foreach ($ids as $id) {
            $id = (string) $id;
            if ($id === '') {
                continue;
            }
            
            $clean[] = $id;
        }

        if ($clean === []) {
            throw new \InvalidArgumentException('IDs list must not be empty.');
        }

        return implode(',', $clean);
    }

    /**
     * Validates and JSON-encodes a messages list for bulk API requests.
     * @param array<int, array<string, mixed>> $messages
     */
    public static function encodeMessages(array $messages): string
    {
        self::assertBatchSize($messages);

        $encoded = json_encode($messages, JSON_UNESCAPED_UNICODE);
        if ($encoded === false) {
            throw new \InvalidArgumentException('Failed to encode messages to JSON.');
        }

        return $encoded;
    }
}
