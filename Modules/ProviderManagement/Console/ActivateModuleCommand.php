<?php

namespace Modules\ProviderManagement\Console;

use Illuminate\Console\Command;

class ActivateModuleCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * You can run: php artisan module:activate ProviderManagement
     */
    protected $signature = 'module:activate {module_slug?}';

    /**
     * The console command description.
     */
    protected $description = 'Activate ProviderManagement module (Worksuite-compatible stub).';

    public function handle(): int
    {
        $this->info('ProviderManagement: activation stub complete.');
        return self::SUCCESS;
    }
}
