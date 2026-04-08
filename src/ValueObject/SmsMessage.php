<?php

declare(strict_types=1);

namespace Vetheslav\SmspBy\ValueObject;

final readonly class SmsMessage
{
    /**
     * Creates a typed SMS message request with validation.
     */
    public function __construct(
        private string $msisdn,
        private string $text,
        private ?string $sender = null,
        private ?string $customId = null,
        private ?\DateTimeInterface $sendOn = null,
        private bool $useShortLinks = false,
    ) {
        if ($this->msisdn === '') {
            throw new \InvalidArgumentException('msisdn must be a non-empty string.');
        }
        
        if ($this->text === '') {
            throw new \InvalidArgumentException('text must be a non-empty string.');
        }
        
        if ($this->sender !== null && mb_strlen($this->sender) > 11) {
            throw new \InvalidArgumentException('SMS sender name must be 11 characters or less.');
        }
        
        if ($this->customId !== null && mb_strlen($this->customId) > 20) {
            throw new \InvalidArgumentException('custom_id must be 20 characters or less.');
        }
    }

    /**
     * Builds the payload for the send/sms endpoint, including optional fields.
     */
    public function toSendArray(): array
    {
        $data = [
            'msisdn' => $this->msisdn,
            'text' => $this->text,
        ];

        if ($this->sender !== null) {
            $data['sender'] = $this->sender;
        }

        if ($this->customId !== null) {
            $data['custom_id'] = $this->customId;
        }

        if ($this->sendOn instanceof \DateTimeInterface) {
            $data['send_on_datetime'] = DateTimeFormatter::formatForApi($this->sendOn);
        }

        if ($this->useShortLinks) {
            $data['shortlinks'] = 1;
        }

        return $data;
    }

    /**
     * Builds a single item payload for sendBulk/sms (shortlinks not supported).
     */
    public function toBulkArray(): array
    {
        if ($this->useShortLinks) {
            throw new \InvalidArgumentException('shortlinks is not supported for bulk SMS messages.');
        }

        $data = [
            'msisdn' => $this->msisdn,
            'text' => $this->text,
        ];

        if ($this->sender !== null) {
            $data['sender'] = $this->sender;
        }

        if ($this->customId !== null) {
            $data['custom_id'] = $this->customId;
        }

        if ($this->sendOn instanceof \DateTimeInterface) {
            $data['send_on_datetime'] = DateTimeFormatter::formatForApi($this->sendOn);
        }

        return $data;
    }
}
