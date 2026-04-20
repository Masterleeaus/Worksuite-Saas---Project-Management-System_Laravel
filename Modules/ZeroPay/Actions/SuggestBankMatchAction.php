<?php

namespace Modules\ZeroPay\Actions;

use Modules\ZeroPay\Models\ZeroPayAttempt;
use Modules\ZeroPay\Models\ZeroPayBankMatch;
use Modules\ZeroPay\Models\ZeroPaySession;

class SuggestBankMatchAction
{
    public function execute(ZeroPaySession $session, ?ZeroPayAttempt $attempt = null, array $meta = []): ZeroPayBankMatch
    {
        return ZeroPayBankMatch::query()->create([
            'company_id' => $session->company_id,
            'session_id' => $session->id,
            'attempt_id' => $attempt?->id,
            'bank_reference' => $meta['bank_reference'] ?? null,
            'suggested_amount' => (float) ($meta['suggested_amount'] ?? $attempt?->amount ?? $session->amount),
            'confidence_score' => (float) ($meta['confidence_score'] ?? 70),
            'status' => 'queued',
            'meta' => $meta,
        ]);
    }
}
