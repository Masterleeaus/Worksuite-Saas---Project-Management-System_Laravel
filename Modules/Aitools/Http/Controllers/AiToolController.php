<?php

namespace Modules\Aitools\Http\Controllers;

use App\Helper\Reply;
use App\Http\Controllers\AccountBaseController;
use Illuminate\Http\Request;
use Modules\Aitools\Entities\AiToolRegistry;
use Modules\Aitools\Services\AiToolDispatcher;

class AiToolController extends AccountBaseController
{
    public function index()
    {
        abort_403(!(user()->is_superadmin && module_enabled('Aitools')));

        $this->pageTitle = 'aitools::app.aiTools';
        $this->activeSettingMenu = 'ai_tools_settings';

        $this->tools = AiToolRegistry::query()
            ->withoutGlobalScope(\App\Scopes\CompanyScope::class)
            ->orderBy('tool_name')
            ->get();

        $this->activeTab = 'tools';

        return view('aitools::ai-tools-settings.index', $this->data);
    }

    public function store(Request $request)
    {
        abort_403(!(user()->is_superadmin && module_enabled('Aitools')));

        $request->validate([
            'tool_name' => 'required|string|max:190',
            'risk_level' => 'nullable|string|max:50',
        ]);

        AiToolRegistry::updateOrCreate(
            ['tool_name' => $request->tool_name],
            [
                'company_id' => null,
                'title' => $request->title,
                'description' => $request->description,
                'risk_level' => $request->risk_level ?: 'low',
                'is_enabled' => (bool) $request->is_enabled,
                'input_schema' => $request->input_schema ? json_decode($request->input_schema, true) : null,
            ]
        );

        return Reply::success(__('messages.recordSaved'));
    }

    public function dispatch(Request $request)
    {
        abort_403(!(module_enabled('Aitools') && user()->permission('view_aitools') == 'all'));

        $request->validate([
            'tool_name' => 'required|string',
            'params' => 'array',
        ]);

        $result = AiToolDispatcher::dispatch($request->tool_name, $request->params ?? []);

        if (!($result['ok'] ?? false)) {
            return Reply::error($result['error'] ?? 'Tool failed', $result);
        }

        return Reply::dataOnly($result);
    }
}
