<?php

namespace Modules\CustomerConnect\Services\Channels;

class SendResult
{
    public function __construct(
        public bool $ok,
        public ?string $provider = null,
        public ?string $providerMessageId = null,
        public ?array $providerResponse = null,
        public ?string $error = null,
        public ?array $details = null,
    ) {}

    public static function sent(array $providerResponse = [], ?string $provider = null, ?string $providerMessageId = null): self
    {
        return new self(
            ok: true,
            provider: $providerResponse['provider'] ?? $provider,
            providerMessageId: $providerResponse['provider_message_id'] ?? $providerMessageId,
            providerResponse: $providerResponse,
            error: null,
            details: null,
        );
    }

    public static function failed(string $error, array $details = [], ?string $provider = null): self
    {
        return new self(
            ok: false,
            provider: $provider,
            providerMessageId: null,
            providerResponse: null,
            error: $error,
            details: $details,
        );
    }
}
