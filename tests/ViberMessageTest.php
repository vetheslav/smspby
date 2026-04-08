<?php

declare(strict_types=1);

namespace Vetheslav\SmspBy\Tests;

use PHPUnit\Framework\TestCase;
use Vetheslav\SmspBy\ValueObject\ViberMessage;

final class ViberMessageTest extends TestCase
{
    public function testRejectsTextWithImageWithoutButton(): void
    {
        $this->expectException(\InvalidArgumentException::class);

        new ViberMessage(
            msisdn: '375291234567',
            text: 'Hello',
            imageUrl: 'https://example.com/banner.jpg',
        );
    }

    public function testAllowsImageOnly(): void
    {
        $viberMessage = new ViberMessage(
            msisdn: '375291234567',
            text: '',
            imageUrl: 'https://example.com/banner.png',
        );

        $payload = $viberMessage->toArray();
        $this->assertSame('', $payload['text']);
        $this->assertSame('https://example.com/banner.png', $payload['image_url']);
    }

    public function testRejectsInvalidImageUrl(): void
    {
        $this->expectException(\InvalidArgumentException::class);

        new ViberMessage(
            msisdn: '375291234567',
            text: '',
            imageUrl: 'ftp://example.com/banner.bmp',
        );
    }

    public function testRejectsButtonCaptionWithoutButton(): void
    {
        $this->expectException(\InvalidArgumentException::class);

        new ViberMessage(
            msisdn: '375291234567',
            text: 'Hello',
            buttonCaption: 'Open',
        );
    }
}
