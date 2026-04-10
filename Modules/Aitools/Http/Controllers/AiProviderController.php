<?php

namespace Modules\Aitools\Http\Controllers;

use App\Helper\Reply;
use App\Http\Controllers\AccountBaseController;
use Illuminate\Http\Request;
use Modules\Aitools\Entities\AiProvider;
use Modules\Aitools\Entities\AiModel;

class AiProviderController extends AccountBaseController
{
    public function index()
    {
        abort_403(!(user()->is_superadmin && module_enabled('Aitools')));

        $this->pageTitle = 'aitools::app.aiTools';
        $this->activeSettingMenu = 'ai_tools_settings';

        $this->providers = AiProvider::query()
            ->withoutGlobalScope(\App\Scopes\CompanyScope::class)
            ->orderByDesc('is_default')
            ->orderBy('name')
            ->get();

        $this->models = AiModel::query()
            ->withoutGlobalScope(\App\Scopes\CompanyScope::class)
            ->orderByDesc('is_default')
            ->orderBy('name')
            ->get();

        $this->activeTab = 'providers';

        return view('aitools::ai-tools-settings.index', $this->data);
    }

    public function storeProvider(Request $request)
    {
        abort_403(!(user()->is_superadmin && module_enabled('Aitools')));

        $request->validate([
            'name' => 'required|string|max:190',
            'driver' => 'nullable|string|max:50',
            'base_url' => 'nullable|string|max:255',
        ]);

        if ($request->is_default) {
            AiProvider::withoutGlobalScope(\App\Scopes\CompanyScope::class)->whereNull('company_id')->update(['is_default' => false]);
        }

        $provider = AiProvider::updateOrCreate(
            ['company_id' => null, 'name' => $request->name],
            [
                'driver' => $request->driver ?: 'openai',
                'base_url' => $request->base_url,
                'api_key' => $request->api_key ?: null,
                'is_active' => (bool) $request->is_active,
                'is_default' => (bool) $request->is_default,
                'meta' => $request->meta ? json_decode($request->meta, true) : null,
            ]
        );

        return Reply::success(__('messages.recordSaved'), ['provider_id' => $provider->id]);
    }

    public function storeModel(Request $request)
    {
        abort_403(!(user()->is_superadmin && module_enabled('Aitools')));

        $request->validate([
            'name' => 'required|string|max:190',
            'model_type' => 'required|string|max:30',
            'provider_id' => 'nullable|integer',
        ]);

        if ($request->is_default) {
            AiModel::withoutGlobalScope(\App\Scopes\CompanyScope::class)
                ->whereNull('company_id')
                ->where('model_type', $request->model_type)
                ->update(['is_default' => false]);
        }

        $model = AiModel::updateOrCreate(
            ['company_id' => null, 'provider_id' => $request->provider_id, 'name' => $request->name, 'model_type' => $request->model_type],
            [
                'max_output_tokens' => $request->max_output_tokens ?: null,
                'is_active' => (bool) $request->is_active,
                'is_default' => (bool) $request->is_default,
                'pricing' => $request->pricing ? json_decode($request->pricing, true) : null,
                'meta' => $request->meta ? json_decode($request->meta, true) : null,
            ]
        );

        return Reply::success(__('messages.recordSaved'), ['model_id' => $model->id]);
    }
}
