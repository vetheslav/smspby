# SMSp.by Symfony Client

[![Latest Stable Version](https://poser.pugx.org/vetheslav/smspby/version)](https://packagist.org/packages/vetheslav/smspby)
[![Total Downloads](https://poser.pugx.org/vetheslav/smspby/downloads)](https://packagist.org/packages/vetheslav/smspby)
[![License](https://poser.pugx.org/vetheslav/smspby/license)](https://packagist.org/packages/vetheslav/smspby)

Typed Symfony-friendly client for the SMSp.by HTTP API. The library uses strict DTOs, validates inputs, and returns structured response objects.

Full API reference: `https://smsp.by/integrations/api`

## Requirements

- PHP >= 8.4
- Symfony 8.x

## Installation

```bash
composer require vetheslav/smspby
```

## Quick Start

```php
<?php

use Symfony\Component\HttpClient\HttpClient;
use Vetheslav\SmspBy\Config\Credentials;
use Vetheslav\SmspBy\SmspByClient;
use Vetheslav\SmspBy\ValueObject\SmsMessage;

$credentials = new Credentials('user-msisdn', 'api-key');
$client = SmspByClient::createDefault($credentials);

$response = $client->sms()->send(new SmsMessage(
    msisdn: '375291234567',
    text: 'Hello from SMSp.by',
    sender: 'MyCompany',
));

if ($response->isSuccess()) {
    $messageId = $response->messageId();
}
```

## API Coverage

- User API: balances, sender names
- SMS API: send, send bulk, cost, cost bulk, status by ID/custom ID (single and bulk)
- Viber API: send, send bulk, cost, cost bulk, status by ID/custom ID (single and bulk)
- Templates API: create/update/delete/list for SMS and Viber
  
`SmsBulkSendResponse::messages()` returns an array of `SmsSendResponse` items.

## DTOs and Validation

All outgoing requests use strict value objects (for example `SmsMessage`, `ViberMessage`, `SmsCostMessage`). The client validates common constraints:

- empty values are rejected
- sender/custom_id length limits are enforced
- bulk limits are enforced (max 500 items)
- Viber message composition rules are validated (text/image/button)

## Error Handling

- Transport or HTTP errors throw `TransportException`.
- Invalid JSON responses throw `InvalidResponseException`.
- API-level errors are exposed via response objects (`$response->isSuccess() === false`, `$response->error()` returns `ApiError`).

## Error Codes

Common API error codes are available as constants in `Vetheslav\\SmspBy\\ValueObject\\ApiErrorCodes`.

## Message Status Codes

SMS statuses are available as a backed enum in `Vetheslav\\SmspBy\\ValueObject\\SmsMessageStatus`.
Viber statuses are available as a backed enum in `Vetheslav\\SmspBy\\ValueObject\\ViberMessageStatus`.
Bulk status responses may include `code=false` (message not found in gateway). This case is preserved via
`MessageStatus::isNotFound()` and the raw gateway code in `MessageStatus::gatewayStatus()->rawCode()`.

## License

MIT
