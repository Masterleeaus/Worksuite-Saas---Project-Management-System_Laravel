<?php

namespace Modules\Aitools\Services;

use Illuminate\Support\Facades\Schema;
use Modules\Aitools\Entities\AiProvider;
use Modules\Aitools\Entities\AiModel;
use Modules\Aitools\Entities\AiToolsSetting;

class AiResolver
{
    public static function defaultProvider(?int $companyId = null): ?AiProvider
    {
        if (!Schema::hasTable('ai_providers')) {
            return null;
        }

        $q = AiProvider::query();
        if (!is_null($companyId)) {
            $q->where('company_id', $companyId);
        } else {
            $q->whereNull('company_id');
        }

        $provider = $q->where('is_active', true)->where('is_default', true)->first();
        if ($provider) return $provider;

        return $q->where('is_active', true)->first();
    }

    public static function defaultModel(string $type = 'chat', ?int $companyId = null, ?int $providerId = null): ?AiModel
    {
        if (!Schema::hasTable('ai_models')) {
            return null;
        }

        $q = AiModel::query()->where('model_type', $type)->where('is_active', true);

        if (!is_null($companyId)) {
            $q->where('company_id', $companyId);
        } else {
            $q->whereNull('company_id');
        }

        if (!is_null($providerId)) {
            $q->where('provider_id', $providerId);
        }

        $model = $q->where('is_default', true)->first();
        if ($model) return $model;

        return $q->first();
    }

    /**
     * Bootstrap a default OpenAI provider/model from legacy AiToolsSetting if none exist.
     */
    public static function bootstrapFromLegacySetting(): void
    {
        if (!Schema::hasTable('ai_providers') || !Schema::hasTable('ai_models')) {
            return;
        }

        $setting = AiToolsSetting::first();
        if (!$setting) {
            return;
        }

        $existing = AiProvider::whereNull('company_id')->count();
        if ($existing > 0) {
            return;
        }

        if (empty($setting->chatgpt_api_key) && empty($setting->model_name)) {
            return;
        }

        $provider = AiProvider::create([
            'company_id' => null,
            'name' => 'OpenAI',
            'driver' => 'openai',
            'base_url' => 'https://api.openai.com/v1',
            'api_key' => $setting->chatgpt_api_key,
            'is_active' => true,
            'is_default' => true,
        ]);

        $modelName = $setting->model_name ?: 'gpt-4o-mini';

        $model = AiModel::create([
            'company_id' => null,
            'provider_id' => $provider->id,
            'name' => $modelName,
            'model_type' => 'chat',
            'is_active' => true,
            'is_default' => true,
        ]);

        // Add a default embedding model for KB/RAG.
        AiModel::create([
            'company_id' => null,
            'provider_id' => $provider->id,
            'name' => 'text-embedding-3-small',
            'model_type' => 'embedding',
            'is_active' => true,
            'is_default' => true,
        ]);

        // Link legacy setting (if columns exist)
        try {
            if (Schema::hasColumn('ai_tools_settings', 'provider_id')) {
                $setting->provider_id = $provider->id;
            }
            if (Schema::hasColumn('ai_tools_settings', 'model_id')) {
                $setting->model_id = $model->id;
            }
            $setting->save();
        } catch (\Throwable $e) {
            // ignore
        }
    }
}
