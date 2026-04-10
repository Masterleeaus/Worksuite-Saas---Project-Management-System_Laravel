<?php

namespace Modules\Testimonials\Console;

use Illuminate\Console\Command;

class ActivateModuleCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * You can run: php artisan module:activate Testimonials
     */
    protected $signature = 'module:activate {module_slug?}';

    /**
     * The console command description.
     */
    protected $description = 'Activate Testimonials module (Worksuite-compatible stub).';

    public function handle(): int
    {
        $this->info('Testimonials: activation stub complete.');
        return self::SUCCESS;
    }
}
