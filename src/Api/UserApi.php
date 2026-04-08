<?php

declare(strict_types=1);

namespace Vetheslav\SmspBy\Api;

use Vetheslav\SmspBy\Http\RequestSender;
use Vetheslav\SmspBy\Response\BalanceResponse;
use Vetheslav\SmspBy\Response\SenderNamesResponse;

final readonly class UserApi
{
    /**
     * Creates a user API wrapper using the shared request sender.
     */
    public function __construct(private RequestSender $requestSender)
    {
    }

    /**
     * Fetches SMS and Viber balances for the authenticated account.
     */
    public function balances(): BalanceResponse
    {
        $data = $this->requestSender->get('balances', []);

        return BalanceResponse::fromArray($data);
    }

    /**
     * Fetches approved sender names for SMS and Viber channels.
     */
    public function senderNames(): SenderNamesResponse
    {
        $data = $this->requestSender->get('senderNames', []);

        return SenderNamesResponse::fromArray($data);
    }
}
