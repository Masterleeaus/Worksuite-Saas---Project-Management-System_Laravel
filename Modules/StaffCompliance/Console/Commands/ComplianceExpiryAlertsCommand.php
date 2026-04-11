<?php

namespace Modules\StaffCompliance\Console\Commands;

use Carbon\Carbon;
use Illuminate\Console\Command;
use Modules\StaffCompliance\Entities\WorkerComplianceDocument;
use Modules\StaffCompliance\Notifications\ComplianceExpiryNotification;

/**
 * Sends expiry alert notifications to workers whose compliance documents
 * are due to expire within the configured alert thresholds (default: 30 and 7 days).
 *
 * Schedule this command daily:
 *   php artisan compliance:expiry-alerts
 */
class ComplianceExpiryAlertsCommand extends Command
{
    protected $signature = 'compliance:expiry-alerts';

    protected $description = 'Send expiry alert notifications for compliance documents';

    public function handle(): int
    {
        $alertDays = config('staffcompliance.expiry_alert_days', [30, 7]);

        $sent = 0;

        foreach ($alertDays as $days) {
            $targetDate = Carbon::today()->addDays($days)->toDateString();

            $documents = WorkerComplianceDocument::with(['worker', 'documentType'])
                ->where('status', 'verified')
                ->whereNotNull('expiry_date')
                ->whereDate('expiry_date', $targetDate)
                ->get();

            foreach ($documents as $document) {
                $worker = $document->worker;

                if (!$worker) {
                    continue;
                }

                try {
                    $worker->notify(new ComplianceExpiryNotification($document, $days));
                    $sent++;
                    $this->line("  → Alert sent to {$worker->name} for document: {$document->documentType?->name} (expires in {$days} days)");
                } catch (\Throwable $e) {
                    $this->warn("  ✗ Failed to notify {$worker->name}: {$e->getMessage()}");
                }
            }
        }

        $this->info("Compliance expiry alerts sent: {$sent}");

        return self::SUCCESS;
    }
}
