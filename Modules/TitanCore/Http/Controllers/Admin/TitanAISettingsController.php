<?php
namespace Modules\TitanCore\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;
use Modules\TitanCore\Jobs\SyncAgentsJob;
use Modules\TitanCore\Jobs\SyncTitanDocsKnowledgeJob;
use Modules\TitanCore\Services\TitanAISettingsService;
use Modules\TitanCore\Services\TitanAIRunLogService;
use Modules\TitanCore\Services\KBHealthService;
use Modules\TitanCore\Jobs\ReEmbedMissingChunksJob;
use Modules\TitanCore\Jobs\PublishKbSnapshotJob;
use Modules\TitanCore\Jobs\PublishAgentContractJob;
use Modules\TitanCore\Services\AgentContractService;
use Modules\TitanZero\Services\ZeroGateway;
use Illuminate\Support\Facades\View;

class TitanAISettingsController extends Controller
{
    public function index(TitanAISettingsService $settings, TitanAIRunLogService $runs, KBHealthService $health)
    {
        $tenantId = Auth::user()->tenant_id ?? null;

        $embeddingsEnabled = (bool) $settings->get('ai.embeddings.enabled', $tenantId, false);
        $embedOnSync = (bool) $settings->get('ai.embeddings.embed_on_sync', $tenantId, false);
        $includeDrafts = (bool) $settings->get('ai.knowledge.include_drafts', $tenantId, false);
        $lastTest = $settings->get('ai.last_test', $tenantId, null);

        $latestRuns = $runs->latest($tenantId, 12);

        $needsMigrations = !Schema::hasTable('titan_ai_settings') || !Schema::hasTable('titan_ai_runs');

        // Queue health heuristic:
        // If there are queued runs older than ~5 minutes, or running runs older than ~30 minutes, warn.
        $queueWarning = null;
        if (Schema::hasTable('titan_ai_runs')) {
            $staleQueued = DB::table('titan_ai_runs')
                ->where('tenant_id', $tenantId)
                ->where('status','queued')
                ->where('created_at','<', now()->subMinutes(5))
                ->count();
            $staleRunning = DB::table('titan_ai_runs')
                ->where('tenant_id', $tenantId)
                ->where('status','running')
                ->where('started_at','<', now()->subMinutes(30))
                ->count();
            if ($staleQueued > 0) {
                $queueWarning = 'Background worker may not be running (queued jobs are not being processed).';
            } elseif ($staleRunning > 0) {
                $queueWarning = 'Background worker appears stuck (a run has been running for a long time).';
            }
        }

        $kbHealth = $health->summary($tenantId);

        $kbCollections = [];
        $agents = [];
        if (\DB::getSchemaBuilder()->hasTable('ai_agents')) {
            $rows = \DB::table('ai_agents')->select(['slug','title','kb_collection_key','is_active'])->where('tenant_id',$tenantId)->orderBy('slug')->get();
            $agents = $rows->map(fn($r)=>[
                'slug'=>$r->slug,
                'title'=>$r->title,
                'kb_collection_key'=>$r->kb_collection_key,
                'is_active'=>(int)$r->is_active,
            ])->toArray();
        }

        $agentContracts = [];
        try {
            $contracts = app(AgentContractService::class);
            foreach ($agents as $a) {
                $agentContracts[$a['slug']] = $contracts->activeInfo($a['slug'], $tenantId);
            }
        } catch (\Throwable $e) {
            // ignore
        }

        $aiCost = [
            'today_usd' => 0.0,
            'last7_usd' => 0.0,
            'today_tokens' => 0,
            'last7_tokens' => 0,
            'by_agent_7d' => [],
        ];
        if (\DB::getSchemaBuilder()->hasTable('titan_ai_usage')) {
            $today = now()->startOfDay();
            $week = now()->subDays(7);
            $aiCost['today_usd'] = (float) \DB::table('titan_ai_usage')->where('tenant_id',$tenantId)->where('created_at','>=',$today)->sum('cost_usd');
            $aiCost['last7_usd'] = (float) \DB::table('titan_ai_usage')->where('tenant_id',$tenantId)->where('created_at','>=',$week)->sum('cost_usd');
            $aiCost['today_tokens'] = (int) \DB::table('titan_ai_usage')->where('tenant_id',$tenantId)->where('created_at','>=',$today)->sum('total_tokens');
            $aiCost['last7_tokens'] = (int) \DB::table('titan_ai_usage')->where('tenant_id',$tenantId)->where('created_at','>=',$week)->sum('total_tokens');
            $rows = \DB::table('titan_ai_usage')
                ->selectRaw('coalesce(agent_slug, \'unknown\') as agent, sum(coalesce(cost_usd,0)) as cost_usd, sum(total_tokens) as tokens')
                ->where('tenant_id',$tenantId)->where('created_at','>=',$week)
                ->groupBy('agent')->orderByDesc('cost_usd')->limit(10)->get();
            $aiCost['by_agent_7d'] = $rows->toArray();
        }

        if (\DB::getSchemaBuilder()->hasTable('ai_kb_collections')) {
            $q = \DB::table('ai_kb_collections')->select(['key_slug','title','active_version_id'])->orderBy('title');
            $rows = $tenantId ? (clone $q)->where('tenant_id',$tenantId)->get() : collect();
            $rows = $rows->isNotEmpty() ? $rows : (clone $q)->whereNull('tenant_id')->get();
            $kbCollections = $rows->map(fn($r)=>[
                'key'=>$r->key_slug,
                'title'=>$r->title,
                'active_version_id'=>$r->active_version_id ?? null,
            ])->toArray();
        }

        $layout = View::exists('layouts.main') ? 'layouts.main' : (View::exists('layouts.app') ? 'layouts.app' : 'titancore::layouts.main');

        // API key config (mask actual keys)
        $apiConfig = [
            'default_provider'      => $settings->get('ai.provider.default', $tenantId, config('ai.default', 'openai')),
            'openai_model'          => $settings->get('ai.provider.openai.model', $tenantId, config('ai.providers.openai.model', 'gpt-4o-mini')),
            'anthropic_model'       => $settings->get('ai.provider.anthropic.model', $tenantId, config('ai.providers.anthropic.model', 'claude-3-haiku')),
            'openai_key_set'        => (bool) ($settings->get('ai.provider.openai.api_key', $tenantId) ?: env('OPENAI_API_KEY')),
            'anthropic_key_set'     => (bool) ($settings->get('ai.provider.anthropic.api_key', $tenantId) ?: env('ANTHROPIC_API_KEY')),
        ];

        return view('titancore::admin.ai.settings', compact('layout','embeddingsEnabled','embedOnSync','includeDrafts','latestRuns','lastTest','needsMigrations','queueWarning','kbHealth','kbCollections','aiCost','agents','agentContracts','apiConfig'));
    }

    public function save(Request $r, TitanAISettingsService $settings)
    {
        $tenantId = Auth::user()->tenant_id ?? null;

        $settings->set('ai.embeddings.enabled', (bool)$r->boolean('embeddings_enabled'), $tenantId);
        $settings->set('ai.embeddings.embed_on_sync', (bool)$r->boolean('embed_on_sync'), $tenantId);

        return redirect()->route('titan.core.ai.settings')->with('success', 'Settings saved.');
    }

    public function syncAgents()
    {
        $tenantId = Auth::user()->tenant_id ?? null;
        SyncAgentsJob::dispatch($tenantId);
        return redirect()->route('titan.core.ai.settings')->with('success', 'Agent configuration sync queued.');
    }

    public function syncTitanDocs(TitanAISettingsService $settings)
    {
        $tenantId = Auth::user()->tenant_id ?? null;
        $embedOnSync = (bool) $settings->get('ai.embeddings.embed_on_sync', $tenantId, false);
        $includeDrafts = (bool) $settings->get('ai.knowledge.include_drafts', $tenantId, false);
        $lastTest = $settings->get('ai.last_test', $tenantId, null);

        SyncTitanDocsKnowledgeJob::dispatch($tenantId, $embedOnSync, (bool)$settings->get('ai.knowledge.include_drafts', $tenantId, false), 0);
        return redirect()->route('titan.core.ai.settings')->with('success', 'AI knowledge base sync queued.');
    }

    public function testAgent(Request $r, TitanAISettingsService $settings, TitanAIRunLogService $runs, ZeroGateway $zero)
    {
        $tenantId = Auth::user()->tenant_id ?? null;

        $agentSlug = (string) $r->input('agent_slug', 'quote_agent');
        $input = trim((string) $r->input('input', ''));

        if ($input === '') {
            return redirect()->route('titan.core.ai.settings')->with('success', 'Please provide input for the test.');
        }

        // Always run in propose-only mode; no side effects.
        $res = $zero->runAgent([
            'agent_slug' => $agentSlug,
            'input' => $input,
            'mode' => 'test',
        ], $tenantId);

        // stash last test in settings (tenant scoped) for display
        $settings->set('ai.last_test', [
            'agent_slug' => $agentSlug,
            'input' => $input,
            'result' => $res,
            'at' => now()->toDateTimeString(),
        ], $tenantId);

        return redirect()->route('titan.core.ai.settings')->with('success', 'Test run completed.');
    }


    public function reEmbedMissing(Request $r)
    {
        $tenantId = Auth::user()->tenant_id ?? null;
        $limit = (int) $r->input('limit', 500);
        ReEmbedMissingChunksJob::dispatch($tenantId, $limit);
        return redirect()->route('titan.core.ai.settings')->with('success', 'Re-embedding job queued.');
    }


    public function publishKbSnapshot(Request $r)
    {
        $tenantId = Auth::user()->tenant_id ?? null;
        $collectionKey = (string) $r->input('collection_key', 'kb_general_cleaning');
        $label = trim((string) $r->input('label', '')) ?: ('Publish '.now()->format('Y-m-d H:i'));
        PublishKbSnapshotJob::dispatch($collectionKey, $tenantId, $label, Auth::id());
        return redirect()->route('titan.core.ai.settings')->with('success', 'KB snapshot publish queued.');
    }


    public function publishAgentContract(Request $r)
    {
        $tenantId = Auth::user()->tenant_id ?? null;
        $agentSlug = (string) $r->input('agent_slug', '');
        if ($agentSlug === '') {
            return redirect()->route('titan.core.ai.settings')->with('success','No agent selected.');
        }
        PublishAgentContractJob::dispatch($agentSlug, $tenantId, Auth::id());
        return redirect()->route('titan.core.ai.settings')->with('success', 'Agent contract publish queued.');
    }

    /**
     * Save API keys to titan_ai_settings (encrypted).
     * These override .env at runtime via the OpenAI/Anthropic adapters.
     */
    public function saveApiKeys(Request $r, TitanAISettingsService $settings)
    {
        $tenantId = Auth::user()->tenant_id ?? null;

        $r->validate([
            'openai_api_key'   => 'nullable|string|max:200',
            'anthropic_api_key'=> 'nullable|string|max:200',
            'openai_model'     => 'nullable|string|max:100',
            'anthropic_model'  => 'nullable|string|max:100',
            'ai_provider'      => 'nullable|in:openai,anthropic',
        ]);

        if ($r->filled('openai_api_key')) {
            $settings->set('ai.provider.openai.api_key', encrypt($r->input('openai_api_key')), $tenantId);
        }
        if ($r->filled('anthropic_api_key')) {
            $settings->set('ai.provider.anthropic.api_key', encrypt($r->input('anthropic_api_key')), $tenantId);
        }
        if ($r->filled('openai_model')) {
            $settings->set('ai.provider.openai.model', $r->input('openai_model'), $tenantId);
        }
        if ($r->filled('anthropic_model')) {
            $settings->set('ai.provider.anthropic.model', $r->input('anthropic_model'), $tenantId);
        }
        if ($r->filled('ai_provider')) {
            $settings->set('ai.provider.default', $r->input('ai_provider'), $tenantId);
        }

        return redirect()->route('titan.core.ai.settings')->with('success', 'API keys saved securely.');
    }

}