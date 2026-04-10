<?php
namespace Modules\TitanCore\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Str;

/**
 * AgentBuilderController
 *
 * Provides a full UI for creating, editing, activating/deactivating,
 * and deleting AI agents without touching config files or re-deploying.
 *
 * Routes (all under auth middleware):
 *   GET  /titan-core/ai/agents             index
 *   POST /titan-core/ai/agents             store
 *   GET  /titan-core/ai/agents/{slug}/edit edit
 *   PUT  /titan-core/ai/agents/{slug}      update
 *   DEL  /titan-core/ai/agents/{slug}      destroy
 */
class AgentBuilderController extends Controller
{
    // -----------------------------------------------------------------------
    // Index
    // -----------------------------------------------------------------------

    public function index()
    {
        $tenantId = Auth::user()->tenant_id ?? null;
        $layout   = $this->resolveLayout();

        $agents = [];
        $collections = [];

        if (DB::getSchemaBuilder()->hasTable('ai_agents')) {
            $agents = DB::table('ai_agents')
                ->where('tenant_id', $tenantId)
                ->orderBy('slug')
                ->get()
                ->map(fn($r) => (array) $r)
                ->toArray();
        }

        if (DB::getSchemaBuilder()->hasTable('ai_kb_collections')) {
            $q = DB::table('ai_kb_collections')->select(['key_slug', 'title'])->orderBy('title');
            $rows = $tenantId
                ? (clone $q)->where('tenant_id', $tenantId)->get()
                : collect();
            $rows = $rows->isNotEmpty() ? $rows : (clone $q)->whereNull('tenant_id')->get();
            $collections = $rows->toArray();
        }

        return View::make('titancore::admin.ai.agents.index', compact('layout', 'agents', 'collections'));
    }

    // -----------------------------------------------------------------------
    // Store (create)
    // -----------------------------------------------------------------------

    public function store(Request $r)
    {
        $tenantId = Auth::user()->tenant_id ?? null;

        $data = $r->validate([
            'title'              => 'required|string|max:191',
            'slug'               => 'nullable|string|max:128|regex:/^[a-z0-9_]+$/',
            'description'        => 'nullable|string|max:1000',
            'kb_collection_key'  => 'required|string|max:128',
            'system_prompt'      => 'nullable|string',
            'output_type'        => 'nullable|string|max:100',
            'requires_confirm'   => 'sometimes|boolean',
            'must_cite'          => 'sometimes|boolean',
            'forbidden_topics'   => 'nullable|string',   // comma-separated
            'is_active'          => 'sometimes|boolean',
        ]);

        $slug = $data['slug'] ?? Str::slug($data['title'], '_');

        $meta = [
            'output'                => $data['output_type'] ?? null,
            'requires_confirmation' => (bool) ($data['requires_confirm'] ?? false),
            'must_cite'             => (bool) ($data['must_cite'] ?? false),
            'forbidden_topics'      => $data['forbidden_topics']
                ? array_map('trim', explode(',', $data['forbidden_topics']))
                : [],
            'system_prompt'         => $data['system_prompt'] ?? null,
        ];

        if (DB::getSchemaBuilder()->hasTable('ai_agents')) {
            DB::table('ai_agents')->updateOrInsert(
                ['tenant_id' => $tenantId, 'slug' => $slug],
                [
                    'title'             => $data['title'],
                    'description'       => $data['description'] ?? null,
                    'kb_collection_key' => $data['kb_collection_key'],
                    'meta'              => json_encode($meta),
                    'is_active'         => (bool) ($data['is_active'] ?? true),
                    'updated_at'        => now(),
                    'created_at'        => now(),
                ]
            );
        }

        return redirect()->route('titan.core.ai.agents.index')
            ->with('success', "Agent '{$slug}' created.");
    }

    // -----------------------------------------------------------------------
    // Edit
    // -----------------------------------------------------------------------

    public function edit(string $slug)
    {
        $tenantId = Auth::user()->tenant_id ?? null;
        $layout   = $this->resolveLayout();

        $agent = null;
        if (DB::getSchemaBuilder()->hasTable('ai_agents')) {
            $row = DB::table('ai_agents')
                ->where('tenant_id', $tenantId)
                ->where('slug', $slug)
                ->first();
            if ($row) {
                $agent        = (array) $row;
                $agent['meta'] = json_decode($row->meta ?? '{}', true) ?: [];
            }
        }

        if (!$agent) {
            return redirect()->route('titan.core.ai.agents.index')
                ->with('error', "Agent '{$slug}' not found.");
        }

        $collections = [];
        if (DB::getSchemaBuilder()->hasTable('ai_kb_collections')) {
            $q = DB::table('ai_kb_collections')->select(['key_slug', 'title'])->orderBy('title');
            $rows = $tenantId
                ? (clone $q)->where('tenant_id', $tenantId)->get()
                : collect();
            $rows = $rows->isNotEmpty() ? $rows : (clone $q)->whereNull('tenant_id')->get();
            $collections = $rows->toArray();
        }

        return View::make('titancore::admin.ai.agents.edit', compact('layout', 'agent', 'collections', 'slug'));
    }

    // -----------------------------------------------------------------------
    // Update
    // -----------------------------------------------------------------------

    public function update(Request $r, string $slug)
    {
        $tenantId = Auth::user()->tenant_id ?? null;

        $data = $r->validate([
            'title'              => 'required|string|max:191',
            'description'        => 'nullable|string|max:1000',
            'kb_collection_key'  => 'required|string|max:128',
            'system_prompt'      => 'nullable|string',
            'output_type'        => 'nullable|string|max:100',
            'requires_confirm'   => 'sometimes|boolean',
            'must_cite'          => 'sometimes|boolean',
            'forbidden_topics'   => 'nullable|string',
            'is_active'          => 'sometimes|boolean',
        ]);

        $meta = [
            'output'                => $data['output_type'] ?? null,
            'requires_confirmation' => (bool) ($data['requires_confirm'] ?? false),
            'must_cite'             => (bool) ($data['must_cite'] ?? false),
            'forbidden_topics'      => $data['forbidden_topics']
                ? array_map('trim', explode(',', $data['forbidden_topics']))
                : [],
            'system_prompt'         => $data['system_prompt'] ?? null,
        ];

        if (DB::getSchemaBuilder()->hasTable('ai_agents')) {
            DB::table('ai_agents')
                ->where('tenant_id', $tenantId)
                ->where('slug', $slug)
                ->update([
                    'title'             => $data['title'],
                    'description'       => $data['description'] ?? null,
                    'kb_collection_key' => $data['kb_collection_key'],
                    'meta'              => json_encode($meta),
                    'is_active'         => $r->boolean('is_active'),
                    'updated_at'        => now(),
                ]);
        }

        return redirect()->route('titan.core.ai.agents.index')
            ->with('success', "Agent '{$slug}' updated.");
    }

    // -----------------------------------------------------------------------
    // Destroy
    // -----------------------------------------------------------------------

    public function destroy(Request $r, string $slug)
    {
        $tenantId = Auth::user()->tenant_id ?? null;

        if (DB::getSchemaBuilder()->hasTable('ai_agents')) {
            DB::table('ai_agents')
                ->where('tenant_id', $tenantId)
                ->where('slug', $slug)
                ->delete();
        }

        return redirect()->route('titan.core.ai.agents.index')
            ->with('success', "Agent '{$slug}' deleted.");
    }

    // -----------------------------------------------------------------------
    // Helpers
    // -----------------------------------------------------------------------

    private function resolveLayout(): string
    {
        return View::exists('layouts.main') ? 'layouts.main'
            : (View::exists('layouts.app') ? 'layouts.app'
                : 'titancore::layouts.main');
    }
}
