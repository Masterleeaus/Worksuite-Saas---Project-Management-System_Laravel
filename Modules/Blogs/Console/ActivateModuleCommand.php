<?php

namespace Modules\Blogs\Console;

use Illuminate\Console\Command;

class ActivateModuleCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * You can run: php artisan module:activate Blogs
     */
    protected $signature = 'module:activate {module_slug?}';

    /**
     * The console command description.
     */
    protected $description = 'Activate Blogs module (Worksuite-compatible stub).';

    public function handle(): int
    {
        $this->info('Blogs: activation stub complete.');
        return self::SUCCESS;
    }
}
