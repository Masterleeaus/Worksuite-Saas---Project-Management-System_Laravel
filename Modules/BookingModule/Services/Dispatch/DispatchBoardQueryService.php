<?php

namespace Modules\BookingModule\Services\Dispatch;

use Carbon\Carbon;
use Illuminate\Support\Collection;
use Modules\BookingModule\Entities\Schedule;

class DispatchBoardQueryService
{
    public function buildBoardPayload(?string $date, ?string $workspace): array
    {
        $dateObj = $date ? Carbon::parse($date) : Carbon::today();
        $workspaceId = $workspace ? (int)$workspace : (int)($this->getWorkspaceId() ?? 0);

        $staff = $this->getEligibleStaff($workspaceId);

        $schedules = Schedule::query()
            ->whereDate('date', $dateObj->toDateString())
            ->when($workspaceId > 0, fn($q) => $q->where('workspace', $workspaceId))
            ->orderBy('start_time')
            ->get();

        return [
            'dispatchDate' => $dateObj->toDateString(),
            'workspaceId' => $workspaceId,
            'staff' => $staff,
            'schedules' => $schedules,
        ];
    }

    protected function getEligibleStaff(int $workspaceId): Collection
    {
        // Keep this install-agnostic. Worksuite usually stores users in App\Models\User.
        $userModel = config('auth.providers.users.model', \App\Models\User::class);
        if (!class_exists($userModel)) {
            return collect();
        }

        $q = $userModel::query();

        // Try to scope by company if available.
        if (function_exists('company') && company()) {
            $companyId = company()->id;
            if ($this->hasColumn($userModel, 'company_id')) {
                $q->where('company_id', $companyId);
            }
        }

        // If workspace column exists, scope it.
        if ($workspaceId > 0 && $this->hasColumn($userModel, 'workspace')) {
            $q->where('workspace', $workspaceId);
        }

        return $q->orderBy('name')->get();
    }

    protected function getWorkspaceId(): ?int
    {
        // Several Worksuite builds use workspace() helper.
        if (function_exists('getActiveWorkSpace')) {
            return (int)getActiveWorkSpace();
        }
        if (function_exists('getWorkspaceId')) {
            return (int)getWorkspaceId();
        }
        return null;
    }

    protected function hasColumn(string $modelClass, string $column): bool
    {
        try {
            $instance = new $modelClass();
            $table = $instance->getTable();
            return \Illuminate\Support\Facades\Schema::hasColumn($table, $column);
        } catch (\Throwable $e) {
            return false;
        }
    }
}
