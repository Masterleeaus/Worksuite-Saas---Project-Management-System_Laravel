<?php

namespace Modules\PromotionManagement\Services\Bridge;

class InstantAdsEntityBridge
{
    public function isEnabled(): bool
    {
        return (bool) config('promotionmanagement_ai.bridges.instantads.enabled', false);
    }

    public function mappings(): array
    {
        return (array) config('promotionmanagement_ai.bridges.instantads.entity_map', []);
    }

    public function mapping(string $key): ?array
    {
        $mapping = $this->mappings()[$key] ?? null;

        return is_array($mapping) ? $mapping : null;
    }

    public function resolveTargetModel(string $key): ?string
    {
        $mapping = $this->mapping($key);

        if (!$mapping) {
            return null;
        }

        $targetModel = $mapping['target_model'] ?? null;

        return is_string($targetModel) && class_exists($targetModel) ? $targetModel : null;
    }
}
