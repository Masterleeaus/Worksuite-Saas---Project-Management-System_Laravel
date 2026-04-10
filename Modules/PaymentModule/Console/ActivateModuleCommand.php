<?php

namespace Modules\PaymentModule\Console;

use Illuminate\Console\Command;

class ActivateModuleCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * You can run: php artisan module:activate PaymentModule
     */
    protected $signature = 'module:activate {module_slug?}';

    /**
     * The console command description.
     */
    protected $description = 'Activate PaymentModule module (Worksuite-compatible stub).';

    public function handle(): int
    {
        $this->info('PaymentModule: activation stub complete.');
        return self::SUCCESS;
    }
}
