<?php

namespace Modules\Aitools\Services;

use Modules\Aitools\AI\ClientInterface;
use Modules\Aitools\AI\Adapters\OpenAIClient;
use Modules\Aitools\AI\Adapters\AnthropicClient;
use Modules\Aitools\Entities\AiModel;
use Modules\Aitools\Entities\AiProvider;

/**
 * Builds provider clients from the Providers/Models registry.
 *
 * This gives Aitools a proper runtime spine (not config-only).
 */
class AiClientFactory
{
    private ?ClientInterface $activeClient = null;

    public function getActiveClient(): ?ClientInterface
    {
        return $this->activeClient;
    }

    public function setActiveClient(?ClientInterface $client): void
    {
        $this->activeClient = $client;
    }

    public function makeDefaultChatClient(?int $companyId = null): ?ClientInterface
    {
        $model = AiModel::query()
            ->where('model_type', 'chat')
            ->where('is_active', 1)
            ->where(function ($q) use ($companyId) {
                $q->whereNull('company_id');
                if ($companyId) $q->orWhere('company_id', $companyId);
            })
            ->orderByDesc('is_default')
            ->orderByDesc('id')
            ->first();

        if ($model) {
            return $this->makeForModel($model);
        }

        $provider = $this->defaultProvider($companyId);
        if (!$provider) return null;
        return $this->makeForProvider($provider);
    }

    public function makeForModel(AiModel $model): ?ClientInterface
    {
        $provider = $model->provider_id ? AiProvider::find($model->provider_id) : $this->defaultProvider($model->company_id);
        if (!$provider) {
            return null;
        }
        return $this->makeForProvider($provider);
    }

    public function makeForProvider(AiProvider $provider): ?ClientInterface
    {
        $driver = strtolower((string)$provider->driver);
        $base = $provider->base_url ?: null;
        $key = $provider->api_key ?: null;

        return match ($driver) {
            'anthropic' => new AnthropicClient($key, $base),
            'openai', 'openai_http' => new OpenAIClient($key, $base),
            default => new OpenAIClient($key, $base),
        };
    }

    private function defaultProvider(?int $companyId = null): ?AiProvider
    {
        return AiProvider::query()
            ->where('is_active', 1)
            ->where(function ($q) use ($companyId) {
                $q->whereNull('company_id');
                if ($companyId) $q->orWhere('company_id', $companyId);
            })
            ->orderByDesc('is_default')
            ->orderByDesc('id')
            ->first();
    }
}
