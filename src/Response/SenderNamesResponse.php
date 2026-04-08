<?php

declare(strict_types=1);

namespace Vetheslav\SmspBy\Response;

use Vetheslav\SmspBy\Model\ApiError;
use Vetheslav\SmspBy\ValueObject\SmsSenderName;
use Vetheslav\SmspBy\ValueObject\ViberSenderName;
use Vetheslav\SmspBy\ValueObject\ViberTemplate;

final class SenderNamesResponse extends AbstractResponse
{
    /**
     * Creates a sender names response wrapper.
     * @param SmsSenderName[] $sms
     * @param ViberSenderName[] $viber
     */
    public function __construct(
        bool $success,
        ?ApiError $apiError,
        array $raw,
        private readonly array $sms,
        private readonly array $viber,
    ) {
        parent::__construct($success, $apiError, $raw);
    }

    /**
     * Builds a sender names response from the API payload.
     */
    public static function fromArray(array $data): self
    {
        $success = ($data['status'] ?? true) !== false;
        $error = $success ? null : ApiError::fromMixed($data['error'] ?? null);

        $sms = [];
        foreach (($data['sms'] ?? []) as $item) {
            if (!\is_array($item)) {
                continue;
            }

            if (!isset($item['sender'])) {
                continue;
            }

            $sms[] = new SmsSenderName(
                (string) $item['sender'],
                (bool) ($item['is_default'] ?? false),
            );
        }

        $viber = [];
        foreach (($data['viber'] ?? []) as $item) {
            if (!\is_array($item)) {
                continue;
            }

            if (!isset($item['sender'])) {
                continue;
            }

            $templates = [];
            foreach (($item['templates'] ?? []) as $template) {
                if (!\is_array($template)) {
                    continue;
                }

                if (!isset($template['text'])) {
                    continue;
                }

                $templates[] = new ViberTemplate(
                    (string) $template['text'],
                    (bool) ($template['active'] ?? false),
                );
            }

            $viber[] = new ViberSenderName(
                (string) $item['sender'],
                (bool) ($item['is_default'] ?? false),
                $templates,
            );
        }

        return new self($success, $error, $data, $sms, $viber);
    }

    /**
     * Returns the list of approved SMS sender names.
     * @return SmsSenderName[]
     */
    public function sms(): array
    {
        return $this->sms;
    }

    /**
     * Returns the list of approved Viber sender names and templates.
     * @return ViberSenderName[]
     */
    public function viber(): array
    {
        return $this->viber;
    }

    /**
     * Returns unique SMS sender names as strings.
     * @return string[]
     */
    public function smsNames(): array
    {
        $names = [];
        foreach ($this->sms as $item) {
            $names[] = $item->sender();
        }

        return array_values(array_unique(array_filter($names, static fn (string $name): bool => $name !== '')));
    }
}
