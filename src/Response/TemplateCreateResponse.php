<?php

declare(strict_types=1);

namespace Vetheslav\SmspBy\Response;

use Vetheslav\SmspBy\Model\ApiError;

final class TemplateCreateResponse extends AbstractResponse
{
    /**
     * Creates a template creation response wrapper.
     */
    public function __construct(
        bool $success,
        ?ApiError $apiError,
        array $raw,
        private readonly ?int $templateId,
    ) {
        parent::__construct($success, $apiError, $raw);
    }

    /**
     * Builds a template creation response from the API payload.
     */
    public static function fromArray(array $data): self
    {
        $success = ($data['status'] ?? true) !== false;
        $error = $success ? null : ApiError::fromMixed($data['error'] ?? null);

        return new self(
            $success,
            $error,
            $data,
            isset($data['template_id']) ? (int) $data['template_id'] : null,
        );
    }

    /**
     * Returns the template ID assigned by the platform.
     */
    public function templateId(): ?int
    {
        return $this->templateId;
    }
}
