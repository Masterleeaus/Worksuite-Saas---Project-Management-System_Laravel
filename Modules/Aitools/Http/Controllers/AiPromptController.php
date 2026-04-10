<?php

namespace Modules\Aitools\Http\Controllers;

use App\Helper\Reply;
use App\Http\Controllers\AccountBaseController;
use Illuminate\Http\Request;
use Modules\Aitools\Entities\AiPrompt;

class AiPromptController extends AccountBaseController
{
    public function index()
    {
        abort_403(!(user()->is_superadmin && module_enabled('Aitools')));

        $this->pageTitle = 'aitools::app.aiTools';
        $this->activeSettingMenu = 'ai_tools_settings';

        $this->prompts = AiPrompt::query()
            ->withoutGlobalScope(\App\Scopes\CompanyScope::class)
            ->orderBy('namespace')
            ->orderBy('slug')
            ->orderByDesc('version')
            ->get();

        $this->activeTab = 'prompts';

        return view('aitools::ai-tools-settings.index', $this->data);
    }

    public function store(Request $request)
    {
        abort_403(!(user()->is_superadmin && module_enabled('Aitools')));

        $request->validate([
            'namespace' => 'required|string|max:80',
            'slug' => 'required|string|max:120',
            'version' => 'required|integer|min:1',
            'locale' => 'required|string|max:12',
            'prompt_body' => 'required|string',
        ]);

        AiPrompt::updateOrCreate(
            [
                'company_id' => null,
                'namespace' => $request->namespace,
                'slug' => $request->slug,
                'version' => (int) $request->version,
                'locale' => $request->locale,
            ],
            [
                'status' => $request->status ?: 'active',
                'title' => $request->title,
                'prompt_body' => $request->prompt_body,
                'meta' => $request->meta ? json_decode($request->meta, true) : null,
            ]
        );

        return Reply::success(__('messages.recordSaved'));
    }
}
