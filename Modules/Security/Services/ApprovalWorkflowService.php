<?php

namespace Modules\Security\Services;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Log;

/**
 * SHARED APPROVAL WORKFLOW SERVICE
 * Unified approval logic for InOutPermit, WorkPermit, and other entities
 * Replaces duplicated approval logic across modules
 */
class ApprovalWorkflowService
{
    public function needsApproval(Model $entity): bool
    {
        return is_null($entity->approved_by) || is_null($entity->validated_by);
    }

    public function getApprovalChain(Model $entity): array
    {
        return [
            'unit_owner_approval' => [
                'status' => !is_null($entity->approved_by) ? 'approved' : 'pending',
                'approved_by' => $entity->approved_by,
                'approved_at' => $entity->approved_at ?? null,
            ],
            'building_manager_approval' => [
                'status' => !is_null($entity->approved_bm) ? 'approved' : 'pending',
                'approved_by' => $entity->approved_bm,
                'approved_at' => $entity->approved_bm_at ?? null,
            ],
            'security_validation' => [
                'status' => !is_null($entity->validated_by) ? 'validated' : 'pending',
                'validated_by' => $entity->validated_by,
                'validated_at' => $entity->validated_at ?? null,
            ],
        ];
    }

    public function approveByUnitOwner(Model $entity, int $userId): bool
    {
        return $entity->update([
            'approved_by' => $userId,
            'approved_at' => now(),
        ]);
    }

    public function approveByBuildingManager(Model $entity, int $userId): bool
    {
        return $entity->update([
            'approved_bm' => $userId,
            'approved_bm_at' => now(),
        ]);
    }

    public function validateBySecurity(Model $entity, int $userId): bool
    {
        return $entity->update([
            'validated_by' => $userId,
            'validated_at' => now(),
            'status' => 'validated',
        ]);
    }

    public function reject(Model $entity, int $userId, string $reason): bool
    {
        Log::info("Entity rejected: {$entity->id}", [
            'type' => get_class($entity),
            'rejected_by' => $userId,
            'reason' => $reason,
        ]);

        return $entity->update([
            'status' => 'rejected',
            'rejection_reason' => $reason,
            'rejected_by' => $userId,
            'rejected_at' => now(),
        ]);
    }
}
