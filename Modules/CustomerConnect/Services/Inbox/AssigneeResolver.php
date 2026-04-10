<?php

namespace Modules\CustomerConnect\Services\Inbox;

/**
 * Safe resolver for WorkSuite user lists (assignees).
 * Avoids hard coupling to a specific User model class.
 */
class AssigneeResolver
{
    public function listAssignees(?int $companyId): array
    {
        $candidates = [];

        $models = [
            'App\Models\User',
            'App\User',
            'App\Models\Staff',
        ];

        foreach ($models as $cls) {
            if (class_exists($cls)) {
                try {
                    $q = $cls::query();
                    if ($companyId && $q->getModel()->getTable() && $q->getModel()->isFillable('company_id') || property_exists($q->getModel(), 'company_id')) {
                        // ignore; not reliable
                    }
                    if ($companyId && $q->getModel()->getConnection()) {
                        // best-effort: many WorkSuite builds use company_id on users table
                        $q->where(function($w) use ($companyId) {
                            $w->where('company_id', $companyId)->orWhereNull('company_id');
                        });
                    }
                    $rows = $q->limit(200)->get();
                    foreach ($rows as $u) {
                        $id = (int) ($u->id ?? 0);
                        if (!$id) continue;
                        $name = (string) ($u->name ?? $u->first_name ?? $u->full_name ?? ('User '.$id));
                        $candidates[$id] = $name;
                    }
                    break;
                } catch (\Throwable $e) {
                    // try next
                }
            }
        }

        asort($candidates);
        return $candidates;
    }
}
