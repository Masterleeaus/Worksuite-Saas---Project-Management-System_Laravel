<?php

namespace Modules\FSMSales\Console\Commands;

use Carbon\Carbon;
use Illuminate\Console\Command;
use Modules\FSMSales\Models\FSMRecurringInvoice;
use Modules\FSMSales\Services\InvoiceGenerationService;

/**
 * Generate recurring invoice entries for active service agreements.
 *
 * Usage: php artisan fsm:sales:generate-recurring [--schedule=monthly] [--period=2026-04]
 */
class GenerateRecurringInvoices extends Command
{
    protected $signature = 'fsm:sales:generate-recurring
                            {--schedule=monthly : Billing schedule (per_visit|monthly|quarterly|annual)}
                            {--period= : Period to generate for, e.g. 2026-04 (defaults to current month)}';

    protected $description = 'Generate recurring invoice entries for active FSM service agreements.';

    public function handle(InvoiceGenerationService $service): int
    {
        if (!class_exists(\Modules\FSMServiceAgreement\Models\FSMServiceAgreement::class)) {
            $this->warn('FSMServiceAgreement module is not installed – skipping.');
            return self::SUCCESS;
        }

        $schedule = $this->option('schedule');
        $period   = $this->option('period');

        [$periodStart, $periodEnd] = $this->resolvePeriod($schedule, $period);

        $this->info(sprintf(
            'Generating %s recurring invoices for %s → %s',
            $schedule,
            $periodStart->toDateString(),
            $periodEnd->toDateString()
        ));

        $agreements = \Modules\FSMServiceAgreement\Models\FSMServiceAgreement::where('state', 'active')->get();
        $count = 0;

        foreach ($agreements as $agreement) {
            // Skip if a recurring entry already exists for this agreement + period
            $exists = FSMRecurringInvoice::where('agreement_id', $agreement->id)
                ->where('billing_schedule', $schedule)
                ->where('period_start', $periodStart->toDateString())
                ->exists();

            if ($exists) {
                continue;
            }

            $service->generateRecurringEntry($agreement, $schedule, $periodStart, $periodEnd);
            $count++;
        }

        $this->info("Created {$count} recurring invoice entr" . ($count === 1 ? 'y' : 'ies') . '.');

        return self::SUCCESS;
    }

    /**
     * Resolve the start and end of the billing period from CLI option.
     *
     * @return array{Carbon, Carbon}
     */
    private function resolvePeriod(string $schedule, ?string $period): array
    {
        $now = $period ? Carbon::parse($period . '-01') : now()->startOfMonth();

        return match ($schedule) {
            'quarterly' => [
                $now->copy()->firstOfQuarter()->startOfDay(),
                $now->copy()->lastOfQuarter()->endOfDay(),
            ],
            'annual' => [
                $now->copy()->startOfYear()->startOfDay(),
                $now->copy()->endOfYear()->endOfDay(),
            ],
            default => [ // monthly
                $now->copy()->startOfMonth()->startOfDay(),
                $now->copy()->endOfMonth()->endOfDay(),
            ],
        };
    }
}
