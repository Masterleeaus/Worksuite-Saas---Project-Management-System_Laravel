<?php

namespace Modules\Accountings\Services;

use Illuminate\Support\Str;
use Modules\Accountings\Entities\FinancialSignal;
use Modules\Accountings\Events\AccountingSignalEmitted;

class AccountingSignalService
{
    public function emit(string $signalName, int $companyId, array $payload = [], array $meta = []): FinancialSignal
    {
        $signal = FinancialSignal::query()->create([
            'signal_id' => (string) Str::uuid(),
            'signal_name' => $signalName,
            'schema_version' => '1.0',
            'company_id' => $companyId,
            'actor_id' => $meta['actor_id'] ?? auth()->id(),
            'source_type' => $meta['source_type'] ?? 'accountings',
            'source_id' => $meta['source_id'] ?? null,
            'correlation_id' => $meta['correlation_id'] ?? null,
            'causation_id' => $meta['causation_id'] ?? null,
            'occurred_at' => $meta['occurred_at'] ?? now(),
            'risk_level' => $meta['risk_level'] ?? 'low',
            'approval_mode' => $meta['approval_mode'] ?? 'none',
            'payload' => $payload,
        ]);

        event(new AccountingSignalEmitted($companyId, [
            'signal_id' => $signal->signal_id,
            'signal_name' => $signalName,
        ]));

        return $signal;
    }
}
