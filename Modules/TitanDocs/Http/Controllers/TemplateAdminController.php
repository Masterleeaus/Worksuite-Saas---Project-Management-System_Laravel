<?php
namespace Modules\TitanDocs\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class TemplateAdminController extends Controller
{
    public function index()
    {
        $templates = DB::table('ai_templates')->orderByDesc('id')->limit(300)->get();
        $tenantId = Auth::user()->tenant_id ?? null;

        $kbCollections = [];
        if (DB::getSchemaBuilder()->hasTable('ai_kb_collections')) {
            $q = DB::table('ai_kb_collections')->select(['key_slug','title','scope_type','agent_slug'])->orderBy('title');
            $rows = $tenantId ? (clone $q)->where('tenant_id', $tenantId)->get() : collect();
            $rows = $rows->isNotEmpty() ? $rows : (clone $q)->whereNull('tenant_id')->get();
            $kbCollections = $rows->map(fn($r)=>[
                'key'=>$r->key_slug,
                'title'=>$r->title,
                'scope_type'=>$r->scope_type ?? 'general',
                'agent_slug'=>$r->agent_slug,
            ])->toArray();
        }

        return view('titandocs::admin.templates.index', compact('templates','kbCollections'));
    }

    public function create()
    {
        $tenantId = Auth::user()->tenant_id ?? null;

        $kbCollections = [];
        if (DB::getSchemaBuilder()->hasTable('ai_kb_collections')) {
            $q = DB::table('ai_kb_collections')->select(['key_slug','title','scope_type','agent_slug'])->orderBy('title');
            $rows = $tenantId ? (clone $q)->where('tenant_id', $tenantId)->get() : collect();
            $rows = $rows->isNotEmpty() ? $rows : (clone $q)->whereNull('tenant_id')->get();
            $kbCollections = $rows->map(fn($r)=>[
                'key'=>$r->key_slug,
                'title'=>$r->title,
                'scope_type'=>$r->scope_type ?? 'general',
                'agent_slug'=>$r->agent_slug,
            ])->toArray();
        }

        return view('titandocs::admin.templates.create', compact('kbCollections'));
    }

    public function store(Request $r)
    {
        $data = $r->validate([
            'name' => 'required|string|max:255',
            'slug' => 'required|string|max:255',
            'template_code' => 'required|string|max:255',
            'description' => 'nullable|string',
            'kb_collection_key' => 'nullable|string|max:128',
        ]);

        // Use a safe default category if none exists
        $categoryId = 1;
        if (DB::getSchemaBuilder()->hasTable('ai_template_categories')) {
            $first = DB::table('ai_template_categories')->orderBy('id')->first();
            if ($first && isset($first->id)) $categoryId = (string)$first->id;
        }

        $insert = [
            'name' => $data['name'],
            'slug' => $data['slug'],
            'template_code' => $data['template_code'],
            'description' => $data['description'] ?? null,
            'icon' => null,
            'status' => 1,
            'professional' => 0,
            'category_id' => (string)$categoryId,
            'type' => '0', // custom
            'form_fields' => null,
            'is_tone' => 1,
            'created_at' => now(),
            'updated_at' => now(),
        ];

        if (DB::getSchemaBuilder()->hasColumn('ai_templates','kb_collection_key')) {
            $insert['kb_collection_key'] = $data['kb_collection_key'] ?: 'kb_general_cleaning';
        }

        DB::table('ai_templates')->insert($insert);

        return redirect()->route('titan.docs.templates.index')->with('success', 'Template created.');
    }

    public function edit(int $id)
    {
        $tpl = DB::table('ai_templates')->where('id',$id)->first();
        abort_if(!$tpl, 404);

        $tenantId = Auth::user()->tenant_id ?? null;
        $kbCollections = [];
        if (DB::getSchemaBuilder()->hasTable('ai_kb_collections')) {
            $q = DB::table('ai_kb_collections')->select(['key_slug','title','scope_type','agent_slug'])->orderBy('title');
            $rows = $tenantId ? (clone $q)->where('tenant_id', $tenantId)->get() : collect();
            $rows = $rows->isNotEmpty() ? $rows : (clone $q)->whereNull('tenant_id')->get();
            $kbCollections = $rows->map(fn($r)=>[
                'key'=>$r->key_slug,
                'title'=>$r->title,
                'scope_type'=>$r->scope_type ?? 'general',
                'agent_slug'=>$r->agent_slug,
            ])->toArray();
        }

        return view('titandocs::admin.templates.edit', compact('tpl','kbCollections'));
    }

    public function update(Request $r, int $id)
    {
        $tpl = DB::table('ai_templates')->where('id',$id)->first();
        abort_if(!$tpl, 404);

        $kb = $r->input('kb_collection_key');
        if ($kb) {
            DB::table('ai_templates')->where('id',$id)->update(['kb_collection_key'=>$kb, 'updated_at'=>now()]);
        }

        return redirect()->route('titan.docs.templates.edit', ['id'=>$id])->with('success', 'Template KB scope updated.');
    }

    public function approve(int $id)
    {
        $tpl = DB::table('ai_templates')->where('id',$id)->first();
        abort_if(!$tpl, 404);

        $data = ['updated_at'=>now()];
        if (DB::getSchemaBuilder()->hasColumn('ai_templates','approved_at')) $data['approved_at'] = now();
        if (DB::getSchemaBuilder()->hasColumn('ai_templates','approved_by')) $data['approved_by'] = Auth::id();
        if (DB::getSchemaBuilder()->hasColumn('ai_templates','status')) $data['status'] = 1;

        DB::table('ai_templates')->where('id',$id)->update($data);

        return redirect()->route('titan.docs.templates.edit', ['id'=>$id])->with('success','Template approved.');
    }

    public function unapprove(int $id)
    {
        $tpl = DB::table('ai_templates')->where('id',$id)->first();
        abort_if(!$tpl, 404);

        $data = ['updated_at'=>now()];
        if (DB::getSchemaBuilder()->hasColumn('ai_templates','approved_at')) $data['approved_at'] = null;
        if (DB::getSchemaBuilder()->hasColumn('ai_templates','approved_by')) $data['approved_by'] = null;

        DB::table('ai_templates')->where('id',$id)->update($data);

        return redirect()->route('titan.docs.templates.edit', ['id'=>$id])->with('success','Template unapproved.');
    }
}
