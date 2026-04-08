<?php

declare(strict_types=1);

namespace Vetheslav\SmspBy\Response;

use Vetheslav\SmspBy\Model\ApiError;

abstract class AbstractResponse
{
    /**
     * Creates a new instance.
     */
    public function __construct(
        private readonly bool $success,
        private readonly ?ApiError $apiError,
        private readonly array $raw,
    ) {
    }

    /**
     * Returns whether the API call was successful.
     */
    public function isSuccess(): bool
    {
        return $this->success;
    }

    /**
     * Returns the structured error payload when the call failed.
     */
    public function error(): ?ApiError
    {
        return $this->apiError;
    }

    /**
     * Returns the raw API payload as decoded from JSON.
     */
    public function raw(): array
    {
        return $this->raw;
    }
}
