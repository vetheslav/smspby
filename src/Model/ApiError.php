<?php

declare(strict_types=1);

namespace Vetheslav\SmspBy\Model;

final readonly class ApiError
{
    /**
     * Creates a structured API error instance.
     */
    public function __construct(
        private ?int $code,
        private string $description,
        private array $raw,
    ) {
        if ($this->description === '') {
            throw new \InvalidArgumentException('Error description must be a non-empty string.');
        }
    }

    /**
     * Normalizes the API error payload into a structured ApiError instance.
     */
    public static function fromMixed(mixed $error): ?self
    {
        if ($error === null) {
            return null;
        }

        if (\is_string($error) && $error !== '') {
            return new self(null, $error, ['description' => $error]);
        }

        if (\is_array($error)) {
            $description = $error['description'] ?? '';
            if (!\is_string($description) || $description === '') {
                $description = json_encode($error, JSON_UNESCAPED_UNICODE) ?: 'Unknown error';
            }

            $code = null;
            if (isset($error['code']) && $error['code'] !== false) {
                $code = (int) $error['code'];
            }

            return new self($code, $description, $error);
        }

        return new self(null, (string) $error, ['description' => (string) $error]);
    }

    /**
     * Returns the numeric error code, if provided by the API.
     */
    public function code(): ?int
    {
        return $this->code;
    }

    /**
     * Returns the error description from the API.
     */
    public function description(): string
    {
        return $this->description;
    }

    /**
     * Returns the raw error payload as received from the API.
     */
    public function raw(): array
    {
        return $this->raw;
    }
}
