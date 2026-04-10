<?php

namespace Modules\CustomerConnect\Services\Inbox;

use Illuminate\Support\Facades\DB;

class InboundAutoAssignHook
{
    public function ensureAssigned(int $threadId, int $companyId): void
    {
        $thread = DB::table('customerconnect_threads')->where('id', $threadId)->first();
        if (!$thread || !empty($thread->assigned_to_user_id)) {
            return;
        }

        // Find assignable users (best-effort; adjust query if your user table differs)
        $users = DB::table('users')
            ->where('company_id', $companyId)
            ->whereNull('deleted_at')
            ->select('id')
            ->orderBy('id', 'asc')
            ->get();

        if ($users->isEmpty()) {
            return;
        }

        // Round robin cursor per company
        $cursor = DB::table('customerconnect_assignment_cursors')->where('company_id', $companyId)->first();
        $idx = 0;
        if ($cursor && isset($cursor->cursor_index)) {
            $idx = ((int)$cursor->cursor_index) % $users->count();
        }

        $assigneeId = (int)$users[$idx]->id;

        DB::table('customerconnect_threads')->where('id', $threadId)->update([
            'assigned_to_user_id' => $assigneeId,
            'updated_at' => now(),
        ]);

        if ($cursor) {
            DB::table('customerconnect_assignment_cursors')->where('company_id', $companyId)->update([
                'cursor_index' => $idx + 1,
                'updated_at' => now(),
            ]);
        } else {
            DB::table('customerconnect_assignment_cursors')->insert([
                'company_id' => $companyId,
                'cursor_index' => $idx + 1,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}
