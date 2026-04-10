<?php

namespace Modules\CustomerConnect\Services\Channels;

class OutboundMessage
{
    public function __construct(
        public int|string|null $companyId,
        public string $channel, // email|sms|whatsapp|telegram
        public string $to,      // email address, E.164 phone, or telegram chat id
        public string $body,
        public ?string $subject = null,
        public array $meta = [],
    ) {}
}
