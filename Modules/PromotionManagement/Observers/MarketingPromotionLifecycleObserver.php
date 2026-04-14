<?php

namespace Modules\PromotionManagement\Observers;

use Illuminate\Database\Eloquent\Model;
use Modules\PromotionManagement\Events\MarketingPromotionLifecycleSignal;

class MarketingPromotionLifecycleObserver
{
    public function created(Model $model): void
    {
        $this->dispatch($model, 'marketing_promotion_created');
    }

    public function updated(Model $model): void
    {
        $this->dispatch($model, 'marketing_promotion_updated');
    }

    public function deleted(Model $model): void
    {
        $this->dispatch($model, 'marketing_promotion_deleted');
    }

    private function dispatch(Model $model, string $eventType): void
    {
        event(new MarketingPromotionLifecycleSignal(
            eventType: $eventType,
            entityType: class_basename($model),
            entityId: $model->getKey(),
            tenantId: isset($model->company_id) && is_numeric($model->company_id) ? (int) $model->company_id : null,
            facts: [
                'id' => $model->getKey(),
                'status' => $model->status ?? null,
                'is_active' => isset($model->is_active) ? (int) $model->is_active : null,
                'updated_fields' => method_exists($model, 'getDirty') ? array_keys($model->getDirty()) : [],
            ],
        ));
    }
}
