<?php

declare(strict_types=1);

namespace Vetheslav\SmspBy\ValueObject;

final readonly class ViberMessage
{
    /**
     * Creates a typed Viber message request with validation.
     */
    public function __construct(
        private string $msisdn,
        private string $text,
        private ?string $sender = null,
        private ?string $customId = null,
        private ?\DateTimeInterface $sendOn = null,
        private bool $useShortLinks = false,
        private ?int $dlrTimeout = null,
        private ?string $imageUrl = null,
        private ?string $buttonUrl = null,
        private ?string $buttonCallbackNumber = null,
        private ?string $buttonCaption = null,
        private ?string $smsText = null,
        private ?string $smsSender = null,
    ) {
        if ($this->msisdn === '') {
            throw new \InvalidArgumentException('msisdn must be a non-empty string.');
        }
        
        if ($this->text === '' && $this->imageUrl === null) {
            throw new \InvalidArgumentException('text must be a non-empty string unless image_url is provided.');
        }
        
        if ($this->text !== '' && mb_strlen($this->text) > 1000) {
            throw new \InvalidArgumentException('text must be 1000 characters or less.');
        }
        
        if ($this->sender !== null && mb_strlen($this->sender) > 20) {
            throw new \InvalidArgumentException('Viber sender name must be 20 characters or less.');
        }
        
        if ($this->customId !== null && mb_strlen($this->customId) > 20) {
            throw new \InvalidArgumentException('custom_id must be 20 characters or less.');
        }
        
        if ($this->dlrTimeout !== null && ($this->dlrTimeout < 1 || $this->dlrTimeout > 1440)) {
            throw new \InvalidArgumentException('dlr_timeout must be between 1 and 1440 minutes.');
        }
        
        if (($this->smsText !== null && $this->smsSender === null) || ($this->smsText === null && $this->smsSender !== null)) {
            throw new \InvalidArgumentException('sms_text and sms_sender must be provided together.');
        }

        $hasButton = $this->buttonCallbackNumber !== null || $this->buttonUrl !== null;
        if ($this->buttonCaption !== null && !$hasButton) {
            throw new \InvalidArgumentException('button_caption requires button_url or button_callback_number.');
        }

        if ($hasButton && $this->text === '') {
            throw new \InvalidArgumentException('text must be provided when using a button.');
        }

        if ($this->imageUrl !== null) {
            $this->assertImageUrl($this->imageUrl);

            if ($hasButton === false && $this->text !== '') {
                throw new \InvalidArgumentException('image_url with text requires a button.');
            }
        }
    }

    private function assertImageUrl(string $url): void
    {
        $parts = parse_url($url);
        $scheme = $parts['scheme'] ?? '';
        if (!in_array($scheme, ['http', 'https'], true)) {
            throw new \InvalidArgumentException('image_url must be an absolute http/https URL.');
        }

        $path = $parts['path'] ?? '';
        $extension = strtolower(pathinfo($path, PATHINFO_EXTENSION));
        if (!in_array($extension, ['jpg', 'png', 'gif'], true)) {
            throw new \InvalidArgumentException('image_url must point to a jpg, png, or gif image.');
        }
    }

    /**
     * Builds the payload for send/viber or sendBulk/viber, applying composition rules.
     */
    public function toArray(): array
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

        if ($this->dlrTimeout !== null) {
            $data['dlr_timeout'] = $this->dlrTimeout;
        }

        if ($this->imageUrl !== null) {
            $data['image_url'] = $this->imageUrl;
        }

        if ($this->buttonCallbackNumber !== null) {
            $data['button_callback_number'] = $this->buttonCallbackNumber;
        } elseif ($this->buttonUrl !== null) {
            $data['button_url'] = $this->buttonUrl;
        }

        if ($this->buttonCaption !== null) {
            $data['button_caption'] = $this->buttonCaption;
        }

        if ($this->smsText !== null && $this->smsSender !== null) {
            $data['sms_text'] = $this->smsText;
            $data['sms_sender'] = $this->smsSender;
        }

        return $data;
    }
}
