<?php

namespace App\Services\Admin\Plan;

use App\Models\Plan;
use App\Models\AuditLog;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;

class PlanService
{
    public function getAllPlans(): Collection
    {
        return Plan::ordered()->get();
    }

    public function getActivePlans(): Collection
    {
        return Plan::active()->ordered()->get();
    }

    public function getPlanById(int $id): ?Plan
    {
        return Plan::find($id);
    }

    public function createPlan(array $data): Plan
    {
        $data['slug'] = Str::slug($data['name']);
        
        if (isset($data['features']) && is_string($data['features'])) {
            $data['features'] = array_filter(array_map('trim', explode("\n", $data['features'])));
        }

        $plan = Plan::create($data);

        AuditLog::log(
            'created',
            'Plan',
            $plan->ID,
            $plan->name,
            null,
            $plan->toArray(),
            "Created subscription plan: {$plan->name}"
        );

        return $plan;
    }

    public function updatePlan(int $id, array $data): Plan
    {
        $plan = Plan::findOrFail($id);
        $oldValues = $plan->toArray();

        if (isset($data['name'])) {
            $data['slug'] = Str::slug($data['name']);
        }

        if (isset($data['features']) && is_string($data['features'])) {
            $data['features'] = array_filter(array_map('trim', explode("\n", $data['features'])));
        }

        $plan->update($data);

        AuditLog::log(
            'updated',
            'Plan',
            $plan->ID,
            $plan->name,
            $oldValues,
            $plan->fresh()->toArray(),
            "Updated subscription plan: {$plan->name}"
        );

        return $plan->fresh();
    }

    public function deletePlan(int $id): bool
    {
        $plan = Plan::findOrFail($id);

        AuditLog::log(
            'deleted',
            'Plan',
            $plan->ID,
            $plan->name,
            $plan->toArray(),
            null,
            "Deleted subscription plan: {$plan->name}"
        );

        return $plan->delete();
    }

    public function toggleStatus(int $id): Plan
    {
        $plan = Plan::findOrFail($id);
        $plan->update(['is_active' => !$plan->is_active]);

        AuditLog::log(
            $plan->is_active ? 'activated' : 'suspended',
            'Plan',
            $plan->ID,
            $plan->name,
            null,
            null,
            "Plan {$plan->name} " . ($plan->is_active ? 'activated' : 'deactivated')
        );

        return $plan->fresh();
    }
}
