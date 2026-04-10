<?php

namespace Modules\BookingModule\Services\Ai;

use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

class ProposalRunner
{
    public function __construct(protected TitanZeroBridge $bridge) {}

    public function proposeAndLog(array $context, $tenantId = null): array
    {
        $proposals = $this->bridge->proposeActions($context, $tenantId);
        if (!empty($proposals)) {
            Log::info('[TitanAI] proposals', [
                'module' => 'BookingModule',
                'tenant_id' => $tenantId,
                'context' => $context,
                'proposals' => $proposals,
            ]);
        }

        $this->persistProposals($proposals, $context, $tenantId);

        return $proposals;
    }

    /**
     * Persist proposals into core ai_action_proposals if that table exists.
     * Safe-by-default: if core table missing, does nothing.
     */
    protected function persistProposals(array $proposals, array $context, $tenantId = null): void
    {
        try {
            if (!Schema::hasTable('ai_action_proposals')) return;

            foreach ($proposals as $p) {
                if (!is_array($p)) continue;

                $row = [
                    'id'                    => (string) Str::uuid(),
                    'tenant_id'             => $tenantId,
                    'target_module'         => $p['target_module'] ?? ($p['module'] ?? null),
                    'action_type'           => $p['action_type'] ?? ($p['type'] ?? null),
                    'payload'               => json_encode($p['payload'] ?? $p, JSON_UNESCAPED_UNICODE),
                    'confidence'            => $p['confidence'] ?? null,
                    'risk_level'            => $p['risk_level'] ?? ($p['risk'] ?? 'green'),
                    'status'                => 'pending',
                    'required_confirmations' => $p['required_confirmations'] ?? null,
                    'explanation'           => $p['explanation'] ?? null,
                    'evidence_refs'         => json_encode($p['evidence_refs'] ?? ['context' => $context], JSON_UNESCAPED_UNICODE),
                    'approved_by'           => null,
                    'executed_at'           => null,
                    'created_at'            => now(),
                    'updated_at'            => now(),
                ];

                DB::table('ai_action_proposals')->insert($row);
            }
        } catch (\Throwable $e) {
            // swallow; persistence is best-effort
        }
    }
}
