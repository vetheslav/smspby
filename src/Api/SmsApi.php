<?php

declare(strict_types=1);

namespace Vetheslav\SmspBy\Api;

use Vetheslav\SmspBy\Http\RequestSender;
use Vetheslav\SmspBy\Response\SmsBulkCostResponse;
use Vetheslav\SmspBy\Response\SmsBulkSendResponse;
use Vetheslav\SmspBy\Response\SmsCostResponse;
use Vetheslav\SmspBy\Response\SmsSendResponse;
use Vetheslav\SmspBy\Response\StatusBulkResponse;
use Vetheslav\SmspBy\Response\StatusResponse;
use Vetheslav\SmspBy\ValueObject\SmsCostMessage;
use Vetheslav\SmspBy\ValueObject\SmsMessage;

final readonly class SmsApi
{
    /**
     * Creates an SMS API wrapper using the shared request sender.
     */
    public function __construct(private RequestSender $requestSender)
    {
    }

    /**
     * Sends a single SMS message and returns the delivery metadata.
     */
    public function send(SmsMessage $smsMessage): SmsSendResponse
    {
        $data = $this->requestSender->post('send/sms', $smsMessage->toSendArray());

        return SmsSendResponse::fromArray($data);
    }

    /**
     * Sends up to 500 SMS messages in one request.
     * @param SmsMessage[] $messages
     */
    public function sendBulk(array $messages): SmsBulkSendResponse
    {
        $payload = [];
        foreach ($messages as $message) {
            if (!$message instanceof SmsMessage) {
                throw new \InvalidArgumentException('Each message must be an instance of SmsMessage.');
            }
            
            $payload[] = $message->toBulkArray();
        }

        $data = $this->requestSender->post('sendBulk/sms', [
            'messages' => ApiHelpers::encodeMessages($payload),
        ]);

        return SmsBulkSendResponse::fromArray($data);
    }

    /**
     * Calculates the cost of a single SMS without sending it.
     */
    public function cost(SmsCostMessage $smsCostMessage): SmsCostResponse
    {
        $data = $this->requestSender->post('cost/sms', $smsCostMessage->toArray());

        return SmsCostResponse::fromArray($data);
    }

    /**
     * Calculates costs for up to 500 SMS messages without sending them.
     * @param SmsCostMessage[] $messages
     */
    public function costBulk(array $messages): SmsBulkCostResponse
    {
        $payload = [];
        foreach ($messages as $message) {
            if (!$message instanceof SmsCostMessage) {
                throw new \InvalidArgumentException('Each message must be an instance of SmsCostMessage.');
            }
            
            $payload[] = $message->toArray();
        }

        $data = $this->requestSender->post('costBulk/sms', [
            'messages' => ApiHelpers::encodeMessages($payload),
        ]);

        return SmsBulkCostResponse::fromArray($data);
    }

    /**
     * Retrieves SMS delivery status by platform message ID.
     */
    public function statusById(int|string $messageId): StatusResponse
    {
        $data = $this->requestSender->post('status/sms', [
            'message_id' => (string) $messageId,
        ]);

        return StatusResponse::fromSmsArray($data);
    }

    /**
     * Retrieves SMS delivery statuses for up to 500 platform message IDs.
     * @param array<int, int|string> $messageIds
     */
    public function statusBulkById(array $messageIds): StatusBulkResponse
    {
        $data = $this->requestSender->post('statusBulk/sms', [
            'message_ids' => ApiHelpers::joinIds($messageIds),
        ]);

        return StatusBulkResponse::fromSmsArray($data);
    }

    /**
     * Retrieves SMS delivery status by custom_id.
     */
    public function statusByCustomId(string $customId): StatusResponse
    {
        $this->assertCustomId($customId);

        $data = $this->requestSender->post('statusCustom/sms', [
            'custom_id' => $customId,
        ]);

        return StatusResponse::fromSmsArray($data);
    }

    /**
     * Retrieves SMS delivery statuses for up to 500 custom_id values.
     * @param string[] $customIds
     */
    public function statusBulkByCustomId(array $customIds): StatusBulkResponse
    {
        foreach ($customIds as $customId) {
            $this->assertCustomId((string) $customId);
        }

        $data = $this->requestSender->post('statusCustomBulk/sms', [
            'message_ids' => ApiHelpers::joinIds($customIds),
        ]);

        return StatusBulkResponse::fromSmsArray($data);
    }

    private function assertCustomId(string $customId): void
    {
        if ($customId === '') {
            throw new \InvalidArgumentException('custom_id must be a non-empty string.');
        }
        
        if (mb_strlen($customId) > 20) {
            throw new \InvalidArgumentException('custom_id must be 20 characters or less.');
        }
    }
}
