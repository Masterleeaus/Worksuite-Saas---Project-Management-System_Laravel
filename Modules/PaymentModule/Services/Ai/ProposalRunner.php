<?php

namespace Modules\PaymentModule\Services\Ai;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

class ProposalRunner
{
    public function __construct(protected TitanZeroBridge $bridge)
    {
    }

    public function proposeAndLog(array $context, $tenantId = null): array
    {
        $proposals = $this->bridge->proposeActions($context, $tenantId);
        if (!empty($proposals)) {
            Log::info('[TitanAI] proposals', [
                'module' => 'PaymentModule',
                'tenant_id' => $tenantId,
                'context' => $context,
                'proposals' => $proposals,
            ]);
        }
        return $proposals;
    }

    /**
     * Persist proposals into core ai_action_proposals if that table exists.
     * Safe-by-default: if core table missing, does nothing.
     */
    protected function persistProposals(array $proposals, array $context, $tenantId = null): void
    {
        try {
            if (!class_exists(Schema::class) || !Schema::hasTable('ai_action_proposals')) {
                return;
            }

            foreach ($proposals as $proposal) {
                if (!is_array($proposal)) {
                    continue;
                }

                DB::table('ai_action_proposals')->insert([
                    'id' => (string) Str::uuid(),
                    'tenant_id' => $tenantId,
                    'target_module' => $proposal['target_module'] ?? ($proposal['module'] ?? null),
                    'action_type' => $proposal['action_type'] ?? ($proposal['type'] ?? null),
                    'payload' => json_encode($proposal['payload'] ?? $proposal, JSON_UNESCAPED_UNICODE),
                    'confidence' => $proposal['confidence'] ?? null,
                    'risk_level' => $proposal['risk_level'] ?? ($proposal['risk'] ?? 'green'),
                    'status' => 'pending',
                    'required_confirmations' => $proposal['required_confirmations'] ?? null,
                    'explanation' => $proposal['explanation'] ?? null,
                    'evidence_refs' => json_encode($proposal['evidence_refs'] ?? ['context' => $context], JSON_UNESCAPED_UNICODE),
                    'approved_by' => null,
                    'executed_at' => null,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        } catch (\Throwable $e) {
            // swallow; persistence is best-effort
        }
    }
}
