<?php

namespace Modules\ServiceManagement\Observers;

use Modules\ServiceManagement\Entities\ServicePricingRule;
use Modules\ServiceManagement\Services\Ai\ServiceManagementSignalEmitter;

class ServicePricingRuleSignalObserver
{
    public function created(ServicePricingRule $rule): void
    {
        $this->emit($rule, 'created');
    }

    public function updated(ServicePricingRule $rule): void
    {
        $this->emit($rule, 'updated', ['changes' => array_keys($rule->getChanges())]);
    }

    public function deleted(ServicePricingRule $rule): void
    {
        $this->emit($rule, 'deleted');
    }

    private function emit(ServicePricingRule $rule, string $action, array $extra = []): void
    {
        app(ServiceManagementSignalEmitter::class)->emit('service_management.pricing_changed', array_merge([
            'action' => $action,
            'pricing_rule_id' => $rule->id,
            'service_id' => $rule->service_id,
            'company_id' => $rule->company_id,
            'zone_id' => $rule->zone_id,
            'base_price_override' => $rule->base_price_override,
            'is_active' => $rule->is_active,
        ], $extra), $rule->company_id ? (int) $rule->company_id : null);
    }
}
