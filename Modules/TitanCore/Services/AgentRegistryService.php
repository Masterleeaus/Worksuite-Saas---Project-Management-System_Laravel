<?php

namespace Modules\TitanCore\Services;

use Illuminate\Support\Facades\DB;
use Modules\TitanCore\Services\AgentContractService;

/**
 * Resolves agent definitions with tenant override support.
 * Pass 3 integration: read from `ai_agents` when present, otherwise fall back to config defaults.
 */
class AgentRegistryService
{
    public function resolve(string $agentSlug, ?int $tenantId = null): array
    {
        // PASS13: If an active contract exists, use it (immutable, published behaviour).
        try {
            /** @var AgentContractService $contracts */
            $contracts = app(AgentContractService::class);
            $payload = $contracts->activePayload($agentSlug, $tenantId);
            if (is_array($payload) && !empty($payload)) {
                $payload['tenant_id'] = $tenantId;
                return $payload;
            }
        } catch (\Throwable $e) {
            // ignore
        }
        // Try tenant-specific first
        try {
            if ($this->tableExists('ai_agents')) {
                if ($tenantId !== null) {
                    $row = DB::table('ai_agents')->where('slug', $agentSlug)->where('tenant_id', $tenantId)->first();
                    if ($row) {
                        return (array) $row;
                    }
                }
                $row = DB::table('ai_agents')->where('slug', $agentSlug)->whereNull('tenant_id')->first();
                if ($row) {
                    return (array) $row;
                }
            }
        } catch (\Throwable $e) {
            // fall through to config
        }

        // Config fallback
        $defaults = config('titan_agents.default_agents', []);
        $agent = $defaults[$agentSlug] ?? null;

        if (! $agent) {
            return [
                'slug' => $agentSlug,
                'title' => ucfirst(str_replace('_', ' ', $agentSlug)),
                'kb_collection_key' => config('titan_agents.general_collection_key', 'kb_general_cleaning'),
                'is_active' => 1,
                'tenant_id' => $tenantId,
            ];
        }

        return [
            'slug' => $agentSlug,
            'title' => $agent['title'] ?? ucfirst(str_replace('_', ' ', $agentSlug)),
            'kb_collection_key' => $agent['kb_collection_key'] ?? config('titan_agents.general_collection_key', 'kb_general_cleaning'),
            'description' => $agent['description'] ?? null,
            'is_active' => $agent['is_active'] ?? 1,
            'tenant_id' => $tenantId,
        ];
    }

    private function tableExists(string $table): bool
    {
        return DB::getSchemaBuilder()->hasTable($table);
    }

    /**
     * Pass 5: Ensure default agents exist (DB upsert), used by settings + jobs.
     */
    public function syncDefaults(?int $tenantId = null): void
    {
        $defs = config('titancore.titan_agents.agents', []);
        foreach ($defs as $slug => $a) {
            $row = \DB::table('ai_agents')->where('slug',$slug)->where('tenant_id',$tenantId)->first();
            if ($row) {
                \DB::table('ai_agents')->where('id',$row->id)->update([
                    'title' => $a['title'] ?? $row->title,
                    'kb_collection_key' => $a['kb_collection_key'] ?? $row->kb_collection_key,
                    'meta' => json_encode($a['meta'] ?? json_decode($row->meta ?? '[]', true)),
                    'is_active' => (int)($a['is_active'] ?? 1),
                    'updated_at' => now(),
                ]);
            } else {
                \DB::table('ai_agents')->insert([
                    'tenant_id' => $tenantId,
                    'slug' => $slug,
                    'title' => $a['title'] ?? $slug,
                    'kb_collection_key' => $a['kb_collection_key'] ?? 'kb_general_cleaning',
                    'meta' => json_encode($a['meta'] ?? []),
                    'is_active' => (int)($a['is_active'] ?? 1),
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }
    }

}
