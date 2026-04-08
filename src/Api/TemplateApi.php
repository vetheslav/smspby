<?php

declare(strict_types=1);

namespace Vetheslav\SmspBy\Api;

use Vetheslav\SmspBy\Http\RequestSender;
use Vetheslav\SmspBy\Response\SimpleResponse;
use Vetheslav\SmspBy\Response\TemplateCreateResponse;
use Vetheslav\SmspBy\Response\TemplateListResponse;

final readonly class TemplateApi
{
    /**
     * Creates a templates API wrapper using the shared request sender.
     */
    public function __construct(private RequestSender $requestSender)
    {
    }

    /**
     * Creates an SMS template with the provided name and text.
     */
    public function createSms(string $name, string $text): TemplateCreateResponse
    {
        $this->assertNonEmpty($name, 'name');
        $this->assertNonEmpty($text, 'text');

        $data = $this->requestSender->post('templateCreate/sms', [
            'name' => $name,
            'text' => $text,
        ]);

        return TemplateCreateResponse::fromArray($data);
    }

    /**
     * Updates an SMS template name and/or text.
     */
    public function updateSms(int $templateId, ?string $name = null, ?string $text = null): SimpleResponse
    {
        $this->assertUpdateFields($name, $text);

        $payload = ['template_id' => $templateId];
        if ($name !== null) {
            $payload['name'] = $name;
        }
        
        if ($text !== null) {
            $payload['text'] = $text;
        }

        $data = $this->requestSender->post('templateUpdate/sms', $payload);

        return SimpleResponse::fromArray($data);
    }

    /**
     * Deletes an SMS template by template ID.
     */
    public function deleteSms(int $templateId): SimpleResponse
    {
        $data = $this->requestSender->post('templateDelete/sms', [
            'template_id' => $templateId,
        ]);

        return SimpleResponse::fromArray($data);
    }

    /**
     * Returns the list of SMS templates for the account.
     */
    public function listSms(): TemplateListResponse
    {
        $data = $this->requestSender->post('templateList/sms', []);

        return TemplateListResponse::fromArray($data);
    }

    /**
     * Creates a Viber template with the provided name and text.
     */
    public function createViber(string $name, string $text): TemplateCreateResponse
    {
        $this->assertNonEmpty($name, 'name');
        $this->assertNonEmpty($text, 'text');

        $data = $this->requestSender->post('templateCreate/viber', [
            'name' => $name,
            'text' => $text,
        ]);

        return TemplateCreateResponse::fromArray($data);
    }

    /**
     * Updates a Viber template name and/or text.
     */
    public function updateViber(int $templateId, ?string $name = null, ?string $text = null): SimpleResponse
    {
        $this->assertUpdateFields($name, $text);

        $payload = ['template_id' => $templateId];
        if ($name !== null) {
            $payload['name'] = $name;
        }
        
        if ($text !== null) {
            $payload['text'] = $text;
        }

        $data = $this->requestSender->post('templateUpdate/viber', $payload);

        return SimpleResponse::fromArray($data);
    }

    /**
     * Deletes a Viber template by template ID.
     */
    public function deleteViber(int $templateId): SimpleResponse
    {
        $data = $this->requestSender->post('templateDelete/viber', [
            'template_id' => $templateId,
        ]);

        return SimpleResponse::fromArray($data);
    }

    /**
     * Returns the list of Viber templates for the account.
     */
    public function listViber(): TemplateListResponse
    {
        $data = $this->requestSender->post('templateList/viber', []);

        return TemplateListResponse::fromArray($data);
    }

    private function assertUpdateFields(?string $name, ?string $text): void
    {
        if ($name === null && $text === null) {
            throw new \InvalidArgumentException('Either name or text must be provided.');
        }
    }

    private function assertNonEmpty(string $value, string $field): void
    {
        if ($value === '') {
            throw new \InvalidArgumentException($field.' must be a non-empty string.');
        }
    }
}
