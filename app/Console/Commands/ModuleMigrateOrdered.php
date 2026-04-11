<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;
use Nwidart\Modules\Facades\Module;

/**
 * Migrate all modules in dependency-safe order.
 *
 * Usage:
 *   php artisan module:migrate-ordered
 *   php artisan module:migrate-ordered --module=FSMCore
 *   php artisan module:migrate-ordered --fresh
 */
class ModuleMigrateOrdered extends Command
{
    protected $signature = 'module:migrate-ordered
                            {--module= : Migrate only a specific module (respects its deps)}
                            {--fresh   : Run module:migrate-fresh instead of module:migrate}
                            {--seed    : Run seeders after migrating}
                            {--force   : Force the operation in production}';

    protected $description = 'Migrate all modules in dependency-safe order (reads config/module_dependencies.php)';

    public function handle(): int
    {
        $config = config('module_dependencies', []);
        $dependencies = $config['dependencies'] ?? [];
        $explicitOrder = $config['order'] ?? [];

        $targetModule = $this->option('module');
        $force = $this->option('force') ? ['--force' => true] : [];

        // Build sorted list of all enabled modules
        $allModules = collect(Module::allEnabled())->keys()->toArray();

        if (empty($allModules)) {
            $this->warn('No enabled modules found.');
            return 0;
        }

        // Topological sort
        $sorted = $this->topologicalSort($allModules, $dependencies, $explicitOrder);

        if ($targetModule) {
            // If a specific module is requested, ensure its dependencies run first
            $sorted = $this->filterWithDeps($sorted, $targetModule, $dependencies);
        }

        $this->info('Migration order (' . count($sorted) . ' modules):');
        foreach ($sorted as $i => $m) {
            $this->line('  ' . ($i + 1) . '. ' . $m);
        }

        if (! $this->confirm('Proceed?', true)) {
            return 0;
        }

        $command = $this->option('fresh') ? 'module:migrate-fresh' : 'module:migrate';

        foreach ($sorted as $module) {
            $this->info("Migrating: {$module}");
            $params = array_merge(['--module' => $module], $force);

            if ($this->option('seed')) {
                $params['--seed'] = true;
            }

            try {
                Artisan::call($command, $params, $this->output);
            } catch (\Exception $e) {
                $this->error("  Failed: {$e->getMessage()}");
                if (! $this->confirm("Continue with remaining modules?", true)) {
                    return 1;
                }
            }
        }

        $this->info('All modules migrated successfully.');
        return 0;
    }

    /**
     * Topological sort respecting explicit order and dependency constraints.
     */
    private function topologicalSort(array $modules, array $dependencies, array $explicitOrder): array
    {
        $sorted = [];
        $visited = [];

        $visit = function (string $module) use (&$sorted, &$visited, $dependencies, &$visit, $modules) {
            if (isset($visited[$module])) {
                return;
            }
            $visited[$module] = true;

            // Visit dependencies first
            foreach ($dependencies[$module] ?? [] as $dep) {
                if (in_array($dep, $modules)) {
                    $visit($dep);
                }
            }

            $sorted[] = $module;
        };

        // Visit explicit order first to anchor them at the front
        foreach ($explicitOrder as $module) {
            if (in_array($module, $modules)) {
                $visit($module);
            }
        }

        // Visit remaining modules alphabetically
        sort($modules);
        foreach ($modules as $module) {
            $visit($module);
        }

        return $sorted;
    }

    /**
     * Filter sorted list to target module and its transitive dependencies.
     */
    private function filterWithDeps(array $sorted, string $target, array $dependencies): array
    {
        $needed = [$target];
        $queue = [$target];

        while ($dep = array_shift($queue)) {
            foreach ($dependencies[$dep] ?? [] as $d) {
                if (! in_array($d, $needed)) {
                    $needed[] = $d;
                    $queue[] = $d;
                }
            }
        }

        return array_values(array_filter($sorted, fn($m) => in_array($m, $needed)));
    }
}
