<?php

namespace Modules\BookingModule\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

/**
 * EmitBookingCompletedSignalJob
 *
 * Emits a completion signal to any registered automation/AI integrations
 * (e.g. TitanZero) after a booking transitions to 'completed'.
 *
 * Actual signal routing is delegated to the configured automation adapter
 * declared in Config/automation.php.  If no adapter is configured the job
 * is a safe no-op.
 */
class EmitBookingCompletedSignalJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public int $tries = 3;
    public int $timeout = 30;

    public function __construct(
        public readonly int $bookingId,
        public readonly int $companyId,
        public readonly array $facts = [],
    ) {}

    public function handle(): void
    {
        $signals = config('bookingmodule.automation.signals', []);

        if (empty($signals)) {
            return; // no automation configured — safe no-op
        }

        // Future: iterate $signals and dispatch to configured adapter classes.
        // Example:
        // foreach ($signals as $signalKey => $signalConfig) {
        //     if ($handler = $signalConfig['handler'] ?? null) {
        //         app($handler)->emit($signalKey, array_merge($this->facts, [
        //             'booking_id' => $this->bookingId,
        //             'company_id' => $this->companyId,
        //         ]));
        //     }
        // }
    }

    public function uniqueId(): string
    {
        return 'booking-completed-signal-' . $this->bookingId;
    }
}
