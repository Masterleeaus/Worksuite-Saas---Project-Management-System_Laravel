<?php

namespace Modules\FSMStock\Console\Commands;

use Illuminate\Console\Command;
use Modules\FSMStock\Models\FSMStockItem;

class NotifyLowStock extends Command
{
    protected $signature   = 'fsm:stock:notify-low-stock';
    protected $description = 'Send low-stock alerts for items below min_qty';

    public function handle(): void
    {
        $items = FSMStockItem::whereRaw('current_qty < min_qty')->get();

        if ($items->isEmpty()) {
            $this->info('No low-stock items.');
            return;
        }

        $this->info("Low-stock items: {$items->count()}");

        foreach ($items as $item) {
            $this->line("  - {$item->name}: current={$item->current_qty}, min={$item->min_qty}");
        }
    }
}
