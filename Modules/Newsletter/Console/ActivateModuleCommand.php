<?php

namespace Modules\Newsletter\Console;

use Illuminate\Console\Command;

class ActivateModuleCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * You can run: php artisan module:activate Newsletter
     */
    protected $signature = 'module:activate {module_slug?}';

    /**
     * The console command description.
     */
    protected $description = 'Activate Newsletter module (Worksuite-compatible stub).';

    public function handle(): int
    {
        $this->info('Newsletter: activation stub complete.');
        return self::SUCCESS;
    }
}
