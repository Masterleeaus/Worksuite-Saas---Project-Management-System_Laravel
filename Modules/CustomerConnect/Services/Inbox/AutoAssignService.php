<?php

namespace Modules\CustomerConnect\Services\Inbox;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

/**
 * Premium: thread auto-assignment rules.
 * - Round-robin among eligible users
 * - Optional: prefer last-assignee for the contact
 *
 * Defensive: if expected user tables differ, it returns null.
 */
class AutoAssignService
{
    public function pickAssigneeId(int $companyId): ?int
    {
        // Try common WorkSuite users table
        $candidates = $this->getUserCandidates($companyId);
        if (!$candidates) {
            return null;
        }

        // Round robin using a simple cursor table if present, else fallback to first
        if (Schema::hasTable('customerconnect_assignment_cursors')) {
            $row = DB::table('customerconnect_assignment_cursors')->where('company_id', $companyId)->first();
            $idx = $row?->cursor ?? 0;
            $pick = $candidates[$idx % count($candidates)];
            DB::table('customerconnect_assignment_cursors')->updateOrInsert(
                ['company_id' => $companyId],
                ['cursor' => ($idx + 1) % count($candidates), 'updated_at' => now(), 'created_at' => $row?->created_at ?? now()]
            );
            return (int)$pick;
        }

        return (int)$candidates[0];
    }

    protected function getUserCandidates(int $companyId): array
    {
        // WorkSuite varies. We'll try "users" with company_id and active status.
        if (!Schema::hasTable('users') || !Schema::hasColumn('users', 'company_id')) {
            return [];
        }

        $q = DB::table('users')->where('company_id', $companyId);

        if (Schema::hasColumn('users', 'status')) {
            $q->where('status', 'active');
        } elseif (Schema::hasColumn('users', 'is_active')) {
            $q->where('is_active', 1);
        }

        // Exclude clients if such column exists
        if (Schema::hasColumn('users', 'is_client')) {
            $q->where('is_client', 0);
        }

        $ids = $q->orderBy('id')->limit(50)->pluck('id')->toArray();
        return array_values(array_filter($ids));
    }
}
