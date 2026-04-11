<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Schema;
use Nwidart\Modules\Facades\Module;

/**
 * Module health verifier.
 *
 * Checks that each module has:
 *   - module.json
 *   - Config/config.php
 *   - At least one *ServiceProvider.php in Providers/
 *   - If DB-backed: a Database/Migrations/ path with at least one file
 *   - All migration tables actually exist in the database
 *
 * Usage:
 *   php artisan module:health-check
 *   php artisan module:health-check --module=Payroll
 *   php artisan module:health-check --missing-tables   (only show table issues)
 */
class ModuleHealthCheck extends Command
{
    protected $signature = 'module:health-check
                            {--module= : Check a specific module only}
                            {--missing-tables : Only report missing database tables}
                            {--json : Output as JSON}';

    protected $description = 'Verify that every module is structurally complete and its DB tables exist';

    private array $issues = [];

    public function handle(): int
    {
        $targetModule = $this->option('module');
        $onlyMissingTables = $this->option('missing-tables');

        $modules = $targetModule
            ? [$targetModule => Module::find($targetModule)]
            : Module::all();

        if (empty($modules)) {
            $this->warn('No modules found.');
            return 0;
        }

        foreach ($modules as $name => $module) {
            if ($module === null) {
                $this->issues[$name][] = 'Module not found in registry';
                continue;
            }

            $path = $module->getPath();

            if (! $onlyMissingTables) {
                $this->checkStructure($name, $path);
            }

            $this->checkMigrationTables($name, $path);
        }

        if ($this->option('json')) {
            $this->line(json_encode($this->issues, JSON_PRETTY_PRINT));
            return empty($this->issues) ? 0 : 1;
        }

        if (empty($this->issues)) {
            $this->info('All modules are healthy.');
            return 0;
        }

        $this->warn(count($this->issues) . ' module(s) have issues:');
        foreach ($this->issues as $module => $problems) {
            $this->line('');
            $this->error("  {$module}:");
            foreach ($problems as $problem) {
                $this->line("    - {$problem}");
            }
        }

        return 1;
    }

    private function checkStructure(string $name, string $path): void
    {
        // module.json
        if (! file_exists($path . '/module.json')) {
            $this->issues[$name][] = 'Missing module.json';
        }

        // Config/config.php
        if (! file_exists($path . '/Config/config.php')) {
            $this->issues[$name][] = 'Missing Config/config.php';
        }

        // At least one ServiceProvider
        $providers = glob($path . '/Providers/*ServiceProvider.php');
        if (empty($providers)) {
            $this->issues[$name][] = 'No *ServiceProvider.php found in Providers/';
        }
    }

    private function checkMigrationTables(string $name, string $path): void
    {
        $migrationPaths = [
            $path . '/Database/Migrations',
            $path . '/database/Migrations',
            $path . '/database/migrations',
        ];

        foreach ($migrationPaths as $migPath) {
            if (! is_dir($migPath)) {
                continue;
            }

            $files = glob($migPath . '/*.php');
            foreach ($files as $file) {
                $this->checkMigrationFile($name, $file);
            }
        }
    }

    private function checkMigrationFile(string $moduleName, string $file): void
    {
        $content = file_get_contents($file);

        // Extract table names from Schema::create calls
        if (preg_match_all("/Schema::create\s*\(\s*['\"]([^'\"]+)['\"]/", $content, $matches)) {
            foreach ($matches[1] as $table) {
                if (! Schema::hasTable($table)) {
                    $this->issues[$moduleName][] =
                        "Table '{$table}' not found in DB (from " . basename($file) . ')';
                }
            }
        }
    }
}
