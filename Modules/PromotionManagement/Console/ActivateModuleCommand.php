<?php

namespace Modules\PromotionManagement\Console;

use Illuminate\Console\Command;

class ActivateModuleCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * You can run: php artisan module:activate PromotionManagement
     */
    protected $signature = 'module:activate {module_slug?}';

    /**
     * The console command description.
     */
    protected $description = 'Activate PromotionManagement module (Worksuite-compatible stub).';

    public function handle(): int
    {
        $this->info('PromotionManagement: activation stub complete.');
        return self::SUCCESS;
    }
}
