<?php

namespace Modules\ServiceManagement\Observers;

use Modules\ServiceManagement\Entities\Service;
use Modules\ServiceManagement\Services\Ai\ServiceManagementSignalEmitter;

class ServiceSignalObserver
{
    public function created(Service $service): void
    {
        $this->emit('service_management.service_created', $service);
    }

    public function updated(Service $service): void
    {
        $this->emit('service_management.service_updated', $service, [
            'changes' => array_keys($service->getChanges()),
        ]);
    }

    public function deleted(Service $service): void
    {
        $this->emit('service_management.service_deleted', $service);
    }

    private function emit(string $type, Service $service, array $extra = []): void
    {
        app(ServiceManagementSignalEmitter::class)->emit($type, array_merge([
            'service_id' => $service->id,
            'company_id' => $service->company_id,
            'name' => $service->name,
            'category_id' => $service->category_id,
            'sub_category_id' => $service->sub_category_id,
            'is_active' => $service->is_active,
        ], $extra), $service->company_id ? (int) $service->company_id : null);
    }
}
