<?php

namespace Modules\CustomerConnect\Services\Premium;

use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class SlaService
{
    /**
     * Compute basic SLA metrics for a thread.
     * - first_response_seconds: first outbound after inbound
     * - last_response_seconds: time since last inbound without outbound response
     */
    public function computeForThread(int $threadId): array
    {
        $inbound = DB::table('customerconnect_messages')
            ->where('thread_id', $threadId)
            ->where('direction', 'inbound')
            ->orderBy('created_at', 'asc')
            ->first();

        $firstOutboundAfterInbound = null;
        if ($inbound) {
            $firstOutboundAfterInbound = DB::table('customerconnect_messages')
                ->where('thread_id', $threadId)
                ->where('direction', 'outbound')
                ->where('created_at', '>=', $inbound->created_at)
                ->orderBy('created_at', 'asc')
                ->first();
        }

        $lastInbound = DB::table('customerconnect_messages')
            ->where('thread_id', $threadId)
            ->where('direction', 'inbound')
            ->orderBy('created_at', 'desc')
            ->first();

        $lastOutbound = DB::table('customerconnect_messages')
            ->where('thread_id', $threadId)
            ->where('direction', 'outbound')
            ->orderBy('created_at', 'desc')
            ->first();

        $firstResponseSeconds = null;
        if ($inbound && $firstOutboundAfterInbound) {
            $firstResponseSeconds = Carbon::parse($firstOutboundAfterInbound->created_at)
                ->diffInSeconds(Carbon::parse($inbound->created_at));
        }

        $awaitingResponseSeconds = null;
        if ($lastInbound) {
            $lastInboundAt = Carbon::parse($lastInbound->created_at);
            $lastOutboundAt = $lastOutbound ? Carbon::parse($lastOutbound->created_at) : null;

            if (!$lastOutboundAt || $lastOutboundAt->lt($lastInboundAt)) {
                $awaitingResponseSeconds = Carbon::now()->diffInSeconds($lastInboundAt);
            }
        }

        return [
            'first_response_seconds' => $firstResponseSeconds,
            'awaiting_response_seconds' => $awaitingResponseSeconds,
        ];
    }
}
