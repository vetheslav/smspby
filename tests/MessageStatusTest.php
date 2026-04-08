<?php

declare(strict_types=1);

namespace Vetheslav\SmspBy\Tests;

use PHPUnit\Framework\TestCase;
use Vetheslav\SmspBy\Response\StatusResponse;
use Vetheslav\SmspBy\ValueObject\SmsMessageStatus;
use Vetheslav\SmspBy\ValueObject\ViberMessageStatus;

final class MessageStatusTest extends TestCase
{
    public function testSmsStatusUsesEnum(): void
    {
        $statusResponse = StatusResponse::fromSmsArray([
            'status' => true,
            'message_status' => [
                'code' => 3,
                'name' => 'Delivered',
            ],
        ]);

        $messageStatus = $statusResponse->messageStatus();
        $this->assertSame(SmsMessageStatus::Delivered, $messageStatus->code());
    }

    public function testViberStatusUsesEnum(): void
    {
        $statusResponse = StatusResponse::fromViberArray([
            'status' => true,
            'message_status' => [
                'code' => 5,
                'name' => 'Read',
            ],
        ]);

        $messageStatus = $statusResponse->messageStatus();
        $this->assertSame(ViberMessageStatus::Read, $messageStatus->code());
    }

    public function testStatusNotFoundIsPreserved(): void
    {
        $statusResponse = StatusResponse::fromSmsArray([
            'status' => true,
            'message_status' => [
                'code' => false,
                'name' => 'Not found',
            ],
        ]);

        $messageStatus = $statusResponse->messageStatus();
        $this->assertNull($messageStatus->code());
        $this->assertTrue($messageStatus->isNotFound());
        $this->assertSame(false, $messageStatus->gatewayStatus()->rawCode());
    }
}
