<?php

namespace Modules\Aitools\Http\Controllers;

use App\Helper\Reply;
use App\Http\Controllers\AccountBaseController;
use Illuminate\Http\Request;
use Modules\Aitools\Entities\AiKbSource;
use Modules\Aitools\Entities\AiKbDocument;
use Modules\Aitools\Services\KbIngestionService;
use Modules\Aitools\Services\KbSearchService;

class AiKbController extends AccountBaseController
{
    public function sources()
    {
        abort_403(!(user()->is_superadmin && module_enabled('Aitools')));

        $this->pageTitle = __('aitools::app.ai_kb_sources');
        $sources = AiKbSource::orderByDesc('id')->paginate(20);

        return view('aitools::kb.sources', compact('sources'));
    }

    public function storeSource(Request $request)
    {
        abort_403(!(user()->is_superadmin && module_enabled('Aitools')));

        $request->validate([
            'name' => 'required|string|max:190',
            'source_type' => 'required|string|max:50',
            'source_uri' => 'nullable|string',
        ]);

        AiKbSource::create([
            'company_id' => company()->id ?? null,
            'user_id' => user()->id ?? null,
            'name' => $request->name,
            'source_type' => $request->source_type,
            'source_uri' => $request->source_uri,
            'is_active' => true,
            'meta' => [],
        ]);

        return Reply::success(__('messages.recordSaved'));
    }

    public function documents()
    {
        abort_403(!(user()->is_superadmin && module_enabled('Aitools')));

        $this->pageTitle = __('aitools::app.ai_kb_documents');
        $documents = AiKbDocument::orderByDesc('id')->paginate(20);
        $sources = AiKbSource::orderBy('name')->get();

        return view('aitools::kb.documents', compact('documents', 'sources'));
    }

    public function storeDocument(Request $request, KbIngestionService $ingestion)
    {
        abort_403(!(user()->is_superadmin && module_enabled('Aitools')));

        $request->validate([
            'source_id' => 'nullable|integer',
            'title' => 'required|string|max:190',
            'doc_type' => 'required|string|max:50',
            'content' => 'required|string',
            'embed_now' => 'nullable|in:0,1',
        ]);

        $result = $ingestion->ingestText([
            'company_id' => company()->id ?? null,
            'user_id' => user()->id ?? null,
            'source_id' => $request->source_id,
            'title' => $request->title,
            'doc_type' => $request->doc_type,
            'content' => $request->content,
            'embed_now' => (bool) ((int)($request->embed_now ?? 1)),
        ]);

        if (!$result['ok']) {
            return Reply::error($result['reason'] ?? __('messages.somethingWentWrong'));
        }

        return Reply::success(__('messages.recordSaved'));
    }

    public function search(Request $request, KbSearchService $search)
    {
        abort_403(!(user()->is_superadmin && module_enabled('Aitools')));

        $request->validate([
            'query' => 'required|string|max:4000',
            'limit' => 'nullable|integer|min:1|max:20',
        ]);

        $result = $search->search($request->query, (int)($request->limit ?? 5), [
            'company_id' => company()->id ?? null,
            'user_id' => user()->id ?? null,
        ]);

        if (!$result['ok']) {
            return Reply::error($result['reason'] ?? __('messages.somethingWentWrong'));
        }

        return Reply::dataOnly(['results' => $result['results']]);
    }
}
