<?php

namespace Modules\CustomerConnect\Services\Premium;

use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class RetentionService
{
    /**
     * Archive + prune old data safely. Designed to be tenant-safe and idempotent.
     *
     * Strategy:
     * - Archive closed threads older than $archiveDays (sets archived_at).
     * - Archive messages/deliveries older than $archiveDays.
     * - Hard-delete archived messages older than $deleteDays (optional).
     */
    public function run(int $archiveDays = 90, ?int $deleteDays = 365): array
    {
        $now = Carbon::now();
        $archiveCutoff = $now->copy()->subDays(max(1, $archiveDays));

        $out = [
            'archived_threads' => 0,
            'archived_messages' => 0,
            'archived_deliveries' => 0,
            'deleted_messages' => 0,
        ];

        if (DB::getSchemaBuilder()->hasTable('customerconnect_threads')) {
            $out['archived_threads'] = DB::table('customerconnect_threads')
                ->whereNull('archived_at')
                ->whereIn('status', ['closed'])
                ->whereNotNull('last_message_at')
                ->where('last_message_at', '<', $archiveCutoff)
                ->update(['archived_at' => $now]);
        }

        if (DB::getSchemaBuilder()->hasTable('customerconnect_messages')) {
            $out['archived_messages'] = DB::table('customerconnect_messages')
                ->whereNull('archived_at')
                ->where('created_at', '<', $archiveCutoff)
                ->update(['archived_at' => $now]);

            if ($deleteDays !== null) {
                $deleteCutoff = $now->copy()->subDays(max(1, $deleteDays));
                $out['deleted_messages'] = DB::table('customerconnect_messages')
                    ->whereNotNull('archived_at')
                    ->where('archived_at', '<', $deleteCutoff)
                    ->delete();
            }
        }

        if (DB::getSchemaBuilder()->hasTable('customerconnect_deliveries')) {
            $out['archived_deliveries'] = DB::table('customerconnect_deliveries')
                ->whereNull('archived_at')
                ->whereIn('status', ['sent', 'failed', 'skipped'])
                ->whereNotNull('updated_at')
                ->where('updated_at', '<', $archiveCutoff)
                ->update(['archived_at' => $now]);
        }

        Log::info('CustomerConnect retention run complete', $out);

        return $out;
    }
}
