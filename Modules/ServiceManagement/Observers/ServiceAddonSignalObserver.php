<?php

namespace Modules\ServiceManagement\Observers;

use Modules\ServiceManagement\Entities\ServiceAddon;
use Modules\ServiceManagement\Services\Ai\ServiceManagementSignalEmitter;

class ServiceAddonSignalObserver
{
    public function created(ServiceAddon $addon): void
    {
        $this->emit($addon, 'created');
    }

    public function updated(ServiceAddon $addon): void
    {
        $this->emit($addon, 'updated', ['changes' => array_keys($addon->getChanges())]);
    }

    public function deleted(ServiceAddon $addon): void
    {
        $this->emit($addon, 'deleted');
    }

    private function emit(ServiceAddon $addon, string $action, array $extra = []): void
    {
        app(ServiceManagementSignalEmitter::class)->emit('service_management.addon_changed', array_merge([
            'action' => $action,
            'addon_id' => $addon->id,
            'service_id' => $addon->service_id,
            'company_id' => $addon->company_id,
            'name' => $addon->name,
            'price' => $addon->price,
            'is_active' => $addon->is_active,
        ], $extra), $addon->company_id ? (int) $addon->company_id : null);
    }
}
