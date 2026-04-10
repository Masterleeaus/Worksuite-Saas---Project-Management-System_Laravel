<?php

namespace App\Services\Admin\Tenant;

use App\Models\Tenant;
use App\Models\AuditLog;
use Illuminate\Support\Collection;

class TenantService
{
    public function getAllTenants(): Collection
    {
        return Tenant::with('activeSubscription')
            ->withCount(['jobs'])
            ->latest()
            ->get();
    }

    public function getTenantById(int $id): ?Tenant
    {
        return Tenant::with(['subscriptions', 'jobs'])
            ->withCount(['jobs'])
            ->find($id);
    }

    public function updateTenant(int $id, array $data): Tenant
    {
        $tenant = Tenant::findOrFail($id);
        $oldValues = $tenant->toArray();
        
        $tenant->update($data);
        
        AuditLog::log(
            'updated',
            'Tenant',
            $tenant->ID,
            $tenant->business_name,
            $oldValues,
            $tenant->fresh()->toArray(),
            "Updated tenant: {$tenant->business_name}"
        );

        return $tenant->fresh();
    }

    public function toggleStatus(int $id): Tenant
    {
        $tenant = Tenant::findOrFail($id);
        $oldStatus = $tenant->status;
        
        $newStatus = $tenant->status === 'active' ? 'suspended' : 'active';
        $tenant->update(['status' => $newStatus]);

        $action = $newStatus === 'suspended' ? 'suspended' : 'activated';
        
        AuditLog::log(
            $action,
            'Tenant',
            $tenant->ID,
            $tenant->business_name,
            ['status' => $oldStatus],
            ['status' => $newStatus],
            "Tenant {$tenant->business_name} status changed to {$newStatus}"
        );

        return $tenant->fresh();
    }

    public function deleteTenant(int $id): bool
    {
        $tenant = Tenant::findOrFail($id);
        
        AuditLog::log(
            'deleted',
            'Tenant',
            $tenant->ID,
            $tenant->business_name,
            $tenant->toArray(),
            null,
            "Deleted tenant: {$tenant->business_name}"
        );

        return $tenant->delete();
    }

    public function getStatistics(): array
    {
        return [
            'total' => Tenant::count(),
            'active' => Tenant::where('status', 'active')->count(),
            'trial' => Tenant::where('status', 'trial')->count(),
            'suspended' => Tenant::where('status', 'suspended')->count(),
        ];
    }
}
