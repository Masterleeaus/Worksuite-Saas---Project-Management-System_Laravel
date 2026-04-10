<?php

namespace Modules\Faq\Console;

use Illuminate\Console\Command;

class ActivateModuleCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * You can run: php artisan module:activate Faq
     */
    protected $signature = 'module:activate {module_slug?}';

    /**
     * The console command description.
     */
    protected $description = 'Activate Faq module (Worksuite-compatible stub).';

    public function handle(): int
    {
        $this->info('Faq: activation stub complete.');
        return self::SUCCESS;
    }
}
