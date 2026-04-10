<?php

namespace Modules\TitanAgents\Services;

/**
 * TitanAgents runtime wrapper.
 * Pass 3 integration: all execution delegates to TitanZero\Services\ZeroGateway.
 */
class GptService
{
    public function interpretQuery(string $userQuery, ?string $agentSlug = null, ?int $tenantId = null): array
    {
        $envelope = [
            'agent_slug' => $agentSlug ?: 'generic_agent',
            'input' => $userQuery,
            'tenant_id' => $tenantId,
        ];

        /** @var \Modules\TitanZero\Services\ZeroGateway $gw */
        $gw = app(\Modules\TitanZero\Services\ZeroGateway::class);

        return $gw->runAgent($envelope, $tenantId);
    }
}
