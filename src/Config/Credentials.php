<?php

declare(strict_types=1);

namespace Vetheslav\SmspBy\Config;

final readonly class Credentials
{
    /**
     * Creates credentials for API authentication.
     */
    public function __construct(
        private string $user,
        private string $apiKey,
    ) {
        if ($this->user === '' || $this->apiKey === '') {
            throw new \InvalidArgumentException('User and API key must be non-empty strings.');
        }
    }

    /**
     * Returns the configured API user (login/MSISDN).
     */
    public function user(): string
    {
        return $this->user;
    }

    /**
     * Returns the configured API key.
     */
    public function apiKey(): string
    {
        return $this->apiKey;
    }
}
