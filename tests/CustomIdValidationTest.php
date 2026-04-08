<?php

declare(strict_types=1);

namespace Vetheslav\SmspBy\Tests;

use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpClient\MockHttpClient;
use Vetheslav\SmspBy\Config\Credentials;
use Vetheslav\SmspBy\SmspByClient;

final class CustomIdValidationTest extends TestCase
{
    public function testSmsStatusByCustomIdRejectsTooLong(): void
    {
        $smspByClient = new SmspByClient(new MockHttpClient(), new Credentials('user', 'key'));

        $this->expectException(\InvalidArgumentException::class);
        $smspByClient->sms()->statusByCustomId(str_repeat('a', 21));
    }

    public function testViberStatusByCustomIdRejectsTooLong(): void
    {
        $smspByClient = new SmspByClient(new MockHttpClient(), new Credentials('user', 'key'));

        $this->expectException(\InvalidArgumentException::class);
        $smspByClient->viber()->statusByCustomId(str_repeat('b', 21));
    }
}
