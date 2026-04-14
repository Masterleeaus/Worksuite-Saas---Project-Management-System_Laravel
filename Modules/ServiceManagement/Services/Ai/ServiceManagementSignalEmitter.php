<?php

namespace Modules\ServiceManagement\Services\Ai;

class ServiceManagementSignalEmitter
{
    public function emit(string $type, array $payload, ?int $tenantId = null): void
    {
        app(TitanZeroBridge::class)->ingestSignal([
            'type' => $type,
            'payload' => $payload,
        ], $tenantId);
    }
}
