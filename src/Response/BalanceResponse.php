<?php

declare(strict_types=1);

namespace Vetheslav\SmspBy\Response;

use Vetheslav\SmspBy\Model\ApiError;

final class BalanceResponse extends AbstractResponse
{
    /**
     * Creates a balance response wrapper.
     */
    public function __construct(
        bool $success,
        ?ApiError $apiError,
        array $raw,
        private readonly ?float $smsBalance,
        private readonly ?float $viberBalance,
    ) {
        parent::__construct($success, $apiError, $raw);
    }

    /**
     * Builds a balance response from the API payload.
     */
    public static function fromArray(array $data): self
    {
        $success = ($data['status'] ?? true) !== false;
        $error = $success ? null : ApiError::fromMixed($data['error'] ?? null);

        return new self(
            $success,
            $error,
            $data,
            isset($data['sms']) ? (float) $data['sms'] : null,
            isset($data['viber']) ? (float) $data['viber'] : null,
        );
    }

    /**
     * Returns the SMS balance for the account.
     */
    public function smsBalance(): ?float
    {
        return $this->smsBalance;
    }

    /**
     * Returns the Viber balance for the account.
     */
    public function viberBalance(): ?float
    {
        return $this->viberBalance;
    }
}
