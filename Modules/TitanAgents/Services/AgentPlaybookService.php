<?php
namespace Modules\TitanAgents\Services;

use Illuminate\Support\Facades\Config;

class AgentPlaybookService
{
    public function get(string $agentSlug): array
    {
        $all = Config::get('titanagents.agents', []);
        return $all[$agentSlug] ?? [];
    }
}
