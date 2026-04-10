<?php

namespace Modules\ZoneManagement\Console;

use Illuminate\Console\Command;
use Modules\ZoneManagement\Entities\CleanerLocation;
use Modules\ZoneManagement\Entities\GpsSetting;
use Modules\ZoneManagement\Entities\RoutePoint;

/**
 * Purge old GPS data rows according to each company's data-retention policy.
 *
 * Schedule: daily   (add to app/Console/Kernel.php or AppServiceProvider)
 *   $schedule->command('gps:purge-old-data')->daily();
 */
class PurgeOldGpsDataCommand extends Command
{
    protected $signature   = 'gps:purge-old-data';
    protected $description = 'Delete cleaner location pings and route points older than the configured retention period.';

    public function handle(): int
    {
        $settings = GpsSetting::all();

        if ($settings->isEmpty()) {
            // Apply global defaults when no company-specific settings exist
            $this->purgeGlobal(30, 90);
        } else {
            foreach ($settings as $s) {
                $this->purgeForCompany(
                    $s->company_id,
                    $s->location_data_retention_days ?? 30,
                    $s->route_data_retention_days    ?? 90
                );
            }
        }

        $this->info('GPS data purge complete.');

        return self::SUCCESS;
    }

    private function purgeForCompany(?int $companyId, int $locationDays, int $routeDays): void
    {
        $locationCutoff = now()->subDays($locationDays);
        $routeCutoff    = now()->subDays($routeDays);

        $locDeleted = CleanerLocation::query()
            ->when($companyId, fn($q) => $q->where('company_id', $companyId))
            ->where('recorded_at', '<', $locationCutoff)
            ->delete();

        $routeDeleted = RoutePoint::query()
            ->when($companyId, fn($q) => $q->where('company_id', $companyId))
            ->where('recorded_at', '<', $routeCutoff)
            ->delete();

        $this->line("  Company {$companyId}: deleted {$locDeleted} location pings, {$routeDeleted} route points.");
    }

    private function purgeGlobal(int $locationDays, int $routeDays): void
    {
        $locDeleted   = CleanerLocation::where('recorded_at', '<', now()->subDays($locationDays))->delete();
        $routeDeleted = RoutePoint::where('recorded_at', '<', now()->subDays($routeDays))->delete();

        $this->line("  Global: deleted {$locDeleted} location pings, {$routeDeleted} route points.");
    }
}
