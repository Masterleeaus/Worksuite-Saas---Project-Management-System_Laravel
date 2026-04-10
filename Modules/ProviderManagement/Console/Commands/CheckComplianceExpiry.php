<?php

namespace Modules\ProviderManagement\Console\Commands;

use Illuminate\Console\Command;
use Modules\ProviderManagement\Services\ComplianceExpiryService;

class CheckComplianceExpiry extends Command
{
    protected $signature = 'provider:compliance:check-expiry {--days=30 : Alert window in days}';

    protected $description = 'Check FSM provider compliance document expiry and log/notify upcoming expirations';

    public function handle(ComplianceExpiryService $service): int
    {
        $days     = (int) $this->option('days');
        $expiring = $service->getExpiringSoon(null, $days);

        if ($expiring->isEmpty()) {
            $this->info('No compliance documents expiring soon.');
            return self::SUCCESS;
        }

        $this->warn("Found {$expiring->count()} employee(s) with compliance documents expiring soon:");

        foreach ($expiring as $item) {
            $name    = $item['employee'] ?? "Employee #{$item['employee_id']}";
            $details = collect($item['expiries'])->map(fn($date, $label) => "{$label}: {$date}")->implode(', ');
            $this->line("  • {$name} — {$details}");
        }

        return self::SUCCESS;
    }
}
