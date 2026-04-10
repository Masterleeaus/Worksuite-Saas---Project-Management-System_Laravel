<?php

namespace Modules\ServiceManagement\Console;

use Illuminate\Console\Command;

class ActivateModuleCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * You can run: php artisan module:activate ServiceManagement
     */
    protected $signature = 'module:activate {module_slug?}';

    /**
     * The console command description.
     */
    protected $description = 'Activate ServiceManagement module (Worksuite-compatible stub).';

    public function handle(): int
    {
        $this->info('ServiceManagement: activation stub complete.');
        return self::SUCCESS;
    }
}
