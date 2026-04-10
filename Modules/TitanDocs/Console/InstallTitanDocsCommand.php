<?php

namespace Modules\TitanDocs\Console;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;

class InstallTitanDocsCommand extends Command
{
    protected $signature = 'titandocs:install {--force : Force re-run idempotent seed migrations}';
    protected $description = 'Install TitanDocs (run module migrations and seed default templates/languages/categories).';

    public function handle(): int
    {
        $this->info('Running TitanDocs module migrations...');
        // best-effort: run all migrations (including seeds) from this module path
        Artisan::call('migrate', [
            '--path' => 'Modules/TitanDocs/Database/Migrations',
            '--force' => true,
        ]);
        $this->line(Artisan::output());

        $this->info('TitanDocs install complete.');
        return self::SUCCESS;
    }
}
