<?php

namespace Modules\FSMEquipment\Console\Commands;

use Illuminate\Console\Command;
use Modules\FSMEquipment\Models\EquipmentWarranty;

class WarrantyExpiryAlertCommand extends Command
{
    protected $signature = 'fsm:warranty-expiry-alert';

    protected $description = 'Log equipment warranties expiring within 30 days';

    public function handle(): int
    {
        $threshold = now()->addDays(30)->toDateString();
        $today     = now()->toDateString();

        $expiring = EquipmentWarranty::with('equipment')
            ->where('warranty_end', '>=', $today)
            ->where('warranty_end', '<=', $threshold)
            ->get();

        if ($expiring->isEmpty()) {
            $this->info('No warranties expiring in the next 30 days.');
            return self::SUCCESS;
        }

        $this->warn("Warranties expiring within 30 days ({$expiring->count()}):");
        foreach ($expiring as $warranty) {
            $daysLeft = (int) now()->startOfDay()->diffInDays($warranty->warranty_end);
            $this->line(sprintf(
                '  • [%s] %s — expires %s (%d day(s) remaining) [Supplier: %s | Ref: %s]',
                $warranty->equipment?->name ?? 'Equipment #' . $warranty->equipment_id,
                $warranty->warranty_number ?? 'no ref',
                $warranty->warranty_end->format('d M Y'),
                $daysLeft,
                $warranty->supplier ?? '—'
            ));
        }

        return self::SUCCESS;
    }
}
