<?php

namespace Modules\FSMServiceAgreement\Console\Commands;

use Carbon\Carbon;
use Illuminate\Console\Command;
use Modules\FSMServiceAgreement\Models\FSMServiceAgreement;

/**
 * Daily scheduled command that:
 *
 *   1. Marks active agreements as expired when their end_date has passed.
 *   2. Generates new FSM orders for active recurring agreements whose next
 *      scheduled occurrence falls on (or before) today.
 *
 * Schedule this command daily in app/Console/Kernel.php:
 *   $schedule->command('fsm:generate-agreement-orders')->daily();
 *
 * Recurrence rule format (JSON stored in fsm_service_agreements.recurrence_rule):
 * {
 *   "frequency": "weekly"|"fortnightly"|"monthly"|"quarterly"|"yearly",
 *   "last_generated": "YYYY-MM-DD"   // updated each run
 * }
 *
 * If no recurrence_rule is set the agreement is treated as a one-off contract
 * and no recurring orders are generated automatically.
 */
class GenerateAgreementOrdersCommand extends Command
{
    protected $signature = 'fsm:generate-agreement-orders
                            {--dry-run : Report what would be done without creating orders}';

    protected $description = 'Expire outdated FSM service agreements and generate recurring orders for active ones';

    public function handle(): int
    {
        $dryRun = (bool) $this->option('dry-run');
        $today  = Carbon::today();

        $this->expireOverdueAgreements($today, $dryRun);
        $this->generateRecurringOrders($today, $dryRun);

        return self::SUCCESS;
    }

    // ── Helpers ───────────────────────────────────────────────────────────────

    /**
     * Transition active agreements whose end_date is in the past to "expired".
     */
    private function expireOverdueAgreements(Carbon $today, bool $dryRun): void
    {
        $overdue = FSMServiceAgreement::where('state', FSMServiceAgreement::STATE_ACTIVE)
            ->whereNotNull('end_date')
            ->whereDate('end_date', '<', $today->toDateString())
            ->get();

        foreach ($overdue as $agreement) {
            if ($dryRun) {
                $this->line("[dry-run] Would expire agreement #{$agreement->id} \"{$agreement->name}\" (ended {$agreement->end_date->toDateString()})");
                continue;
            }

            $agreement->expire();
            $this->line("Expired agreement #{$agreement->id} \"{$agreement->name}\"");
        }

        if ($overdue->isEmpty()) {
            $this->line('No agreements to expire.');
        }
    }

    /**
     * For every active agreement that has a recurrence_rule, check whether the
     * next occurrence is due and, if so, generate the next set of FSM orders.
     */
    private function generateRecurringOrders(Carbon $today, bool $dryRun): void
    {
        $activeWithRule = FSMServiceAgreement::where('state', FSMServiceAgreement::STATE_ACTIVE)
            ->whereNotNull('recurrence_rule')
            ->get()
            ->filter(fn (FSMServiceAgreement $a) => ! empty($a->recurrence_rule['frequency']));

        $totalCreated = 0;

        foreach ($activeWithRule as $agreement) {
            $rule          = $agreement->recurrence_rule;
            $frequency     = $rule['frequency'] ?? null;
            $lastGenerated = isset($rule['last_generated'])
                ? Carbon::parse($rule['last_generated'])
                : $agreement->start_date;

            $nextDue = $this->nextOccurrence($lastGenerated, $frequency);

            if ($nextDue === null || $nextDue->isAfter($today)) {
                continue; // not due yet
            }

            if ($dryRun) {
                $this->line("[dry-run] Would generate orders for agreement #{$agreement->id} \"{$agreement->name}\" (next due {$nextDue->toDateString()})");
                continue;
            }

            $orders = $agreement->generateInitialOrders();
            $count  = $orders->count();
            $totalCreated += $count;

            // Persist updated last_generated date so we don't re-trigger next run.
            $updatedRule = array_merge($rule, ['last_generated' => $today->toDateString()]);
            $agreement->recurrence_rule = $updatedRule;
            $agreement->save();

            $this->line("Agreement #{$agreement->id} \"{$agreement->name}\" — generated {$count} order(s) (next due was {$nextDue->toDateString()})");
        }

        if ($activeWithRule->isEmpty()) {
            $this->line('No active recurring agreements found.');
        } elseif (! $dryRun) {
            $this->info("Total orders created: {$totalCreated}");
        }
    }

    /**
     * Compute the next occurrence date relative to $lastGenerated for the given
     * frequency string.  Returns null for unknown frequencies.
     */
    private function nextOccurrence(Carbon $lastGenerated, ?string $frequency): ?Carbon
    {
        return match ($frequency) {
            'weekly'      => $lastGenerated->copy()->addWeek(),
            'fortnightly' => $lastGenerated->copy()->addWeeks(2),
            'monthly'     => $lastGenerated->copy()->addMonth(),
            'quarterly'   => $lastGenerated->copy()->addMonths(3),
            'yearly'      => $lastGenerated->copy()->addYear(),
            default       => null,
        };
    }
}
