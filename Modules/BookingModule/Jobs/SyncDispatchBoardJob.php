<?php

namespace Modules\BookingModule\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Modules\BookingModule\Entities\Schedule;

/**
 * SyncDispatchBoardJob
 *
 * Lightweight signal job that flushes any dispatch-board specific caches or
 * triggers a refresh after a booking status change.
 *
 * If no caching layer is configured this job is a safe no-op.  Future
 * implementations can add Redis/broadcast refresh here without changing callers.
 */
class SyncDispatchBoardJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public int $tries = 2;
    public int $timeout = 15;

    public function __construct(
        public readonly int $companyId,
        public readonly ?int $scheduleId = null,
    ) {}

    public function handle(): void
    {
        if (!config('bookingmodule.dispatch.enabled', true)) {
            return;
        }

        // Flush any cached dispatch board data keyed by company.
        $cacheKey = 'dispatch_board_' . $this->companyId;
        \Illuminate\Support\Facades\Cache::forget($cacheKey);

        if ($this->scheduleId) {
            \Illuminate\Support\Facades\Cache::forget('dispatch_schedule_' . $this->scheduleId);
        }
    }

    public function uniqueId(): string
    {
        return 'sync-dispatch-' . $this->companyId . '-' . ($this->scheduleId ?? 'all');
    }
}
