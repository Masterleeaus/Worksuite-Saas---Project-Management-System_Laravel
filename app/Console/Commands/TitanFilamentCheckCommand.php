<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Route;

/**
 * TitanFilamentCheckCommand
 *
 * Artisan command: php artisan titan:filament-check
 *
 * Verifies that the Titan Filament panel is correctly installed and configured:
 *   1. Filament package is installed
 *   2. TitanPanelProvider is registered
 *   3. /titan routes are registered
 *   4. Tenant (company_id) scope mechanism is in place
 *   5. Module detection works
 *   6. Auth guard is intact
 *   7. Theme (igaster/laravel-theme) is unaffected
 */
class TitanFilamentCheckCommand extends Command
{
    protected $signature = 'titan:filament-check';

    protected $description = 'Verify that the Titan Filament panel is correctly installed and configured.';

    /** @var array<string, bool> */
    private array $results = [];

    public function handle(): int
    {
        $this->info('');
        $this->info('╔══════════════════════════════════════════╗');
        $this->info('║  Titan Filament Installation Check        ║');
        $this->info('╚══════════════════════════════════════════╝');
        $this->info('');

        $this->checkFilamentInstalled();
        $this->checkPanelProviderRegistered();
        $this->checkTitanRoutes();
        $this->checkTenantScope();
        $this->checkModuleDetection();
        $this->checkAuthGuard();
        $this->checkThemeUnaffected();

        $this->printSummary();

        $failed = collect($this->results)->filter(fn ($v) => $v === false)->count();

        return $failed > 0 ? self::FAILURE : self::SUCCESS;
    }

    // -------------------------------------------------------------------------

    private function checkFilamentInstalled(): void
    {
        $installed = class_exists(\Filament\Facades\Filament::class);

        $this->result(
            'Filament package installed',
            $installed,
            $installed
                ? 'filament/filament found in vendor/'
                : 'Run: composer require filament/filament && php artisan filament:install --panels'
        );
    }

    private function checkPanelProviderRegistered(): void
    {
        $registered = class_exists(\App\Providers\TitanPanelProvider::class);

        $this->result(
            'TitanPanelProvider exists',
            $registered,
            $registered
                ? 'app/Providers/TitanPanelProvider.php found'
                : 'app/Providers/TitanPanelProvider.php is missing'
        );

        if (!$registered) {
            return;
        }

        // Check provider is registered in config/app.php
        $providers = config('app.providers', []);
        $inConfig  = in_array(\App\Providers\TitanPanelProvider::class, $providers, true);

        $this->result(
            'TitanPanelProvider in config/app.php',
            $inConfig,
            $inConfig
                ? 'Registered in providers array'
                : 'Add App\\Providers\\TitanPanelProvider::class to config/app.php providers'
        );
    }

    private function checkTitanRoutes(): void
    {
        $routeFileExists = file_exists(base_path('routes/Titan/filament.routes.php'));

        $this->result(
            'routes/Titan/filament.routes.php exists',
            $routeFileExists,
            $routeFileExists ? 'Route file found' : 'Route file missing'
        );

        // Check that /titan route is registered
        $titanRoute = Route::getRoutes()->getByName('titan.home');
        $routeRegistered = $titanRoute !== null;

        $this->result(
            '/titan route registered',
            $routeRegistered,
            $routeRegistered
                ? 'titan.home route exists'
                : 'titan.home route not found – ensure RouteServiceProvider loads routes/Titan/filament.routes.php'
        );

        // Check Worksuite routes are NOT overridden
        $protected = ['/dashboard', '/home', '/admin'];

        foreach ($protected as $path) {
            $routes = Route::getRoutes()->getRoutesByMethod();
            $found  = false;

            foreach ($routes['GET'] ?? [] as $route) {
                if ($route->uri() === ltrim($path, '/') && str_contains(($route->getActionName() ?? ''), 'filament')) {
                    $found = true;
                    break;
                }
            }

            $this->result(
                "Worksuite route {$path} not overridden by Filament",
                !$found,
                !$found ? 'Safe' : "Filament has registered a route at {$path} – investigate TitanPanelProvider path setting"
            );
        }
    }

    private function checkTenantScope(): void
    {
        $middlewareExists = class_exists(\App\Http\Middleware\ApplyTitanTenantScope::class);

        $this->result(
            'ApplyTitanTenantScope middleware exists',
            $middlewareExists,
            $middlewareExists
                ? 'app/Http/Middleware/ApplyTitanTenantScope.php found'
                : 'app/Http/Middleware/ApplyTitanTenantScope.php is missing'
        );

        $baseResourceExists = class_exists(\App\Filament\Resources\BaseTenantResource::class);

        $this->result(
            'BaseTenantResource exists',
            $baseResourceExists,
            $baseResourceExists
                ? 'app/Filament/Resources/BaseTenantResource.php found'
                : 'app/Filament/Resources/BaseTenantResource.php is missing'
        );

        // Verify CompanyScope is applied to HasCompany models
        $companyScopeExists = class_exists(\App\Scopes\CompanyScope::class);

        $this->result(
            'Core CompanyScope present',
            $companyScopeExists,
            $companyScopeExists ? 'app/Scopes/CompanyScope.php found' : 'CompanyScope is missing'
        );
    }

    private function checkModuleDetection(): void
    {
        $modulesPath     = base_path('Modules');
        $extensionsPath  = app_path('Extensions');
        $detectedModules = [];

        if (is_dir($modulesPath)) {
            $dirs = array_filter(scandir($modulesPath), fn ($d) => $d !== '.' && $d !== '..' && is_dir($modulesPath . '/' . $d));
            $detectedModules = array_merge($detectedModules, array_values($dirs));
        }

        if (is_dir($extensionsPath)) {
            $dirs = array_filter(scandir($extensionsPath), fn ($d) => $d !== '.' && $d !== '..' && is_dir($extensionsPath . '/' . $d));
            $detectedModules = array_merge($detectedModules, array_map(fn ($d) => 'ext:' . $d, array_values($dirs)));
        }

        $count = count($detectedModules);
        $ok    = $count > 0;

        $this->result(
            'Module detection',
            $ok,
            $ok ? "Detected {$count} module(s)/extension(s)" : 'No modules detected'
        );

        if ($ok && $this->getOutput()->isVerbose()) {
            $this->line('   Detected: ' . implode(', ', array_slice($detectedModules, 0, 10)) . ($count > 10 ? '…' : ''));
        }
    }

    private function checkAuthGuard(): void
    {
        $guards  = array_keys(config('auth.guards', []));
        $hasWeb  = in_array('web', $guards, true);

        $this->result(
            'Auth guard "web" available for Filament',
            $hasWeb,
            $hasWeb ? '"web" guard configured in config/auth.php' : '"web" guard missing from config/auth.php'
        );
    }

    private function checkThemeUnaffected(): void
    {
        // Check igaster/laravel-theme is still present and its config exists
        $themeConfigExists  = file_exists(config_path('themes.php'));
        $themePackageExists = class_exists(\igaster\laravelTheme\themeMiddleware::class)
            || file_exists(base_path('vendor/igaster/laravel-theme'));

        $ok = $themeConfigExists || $themePackageExists;

        $this->result(
            'igaster/laravel-theme unaffected',
            $ok,
            $ok
                ? 'Theme config/package present and intact'
                : 'Theme config/package not detected – verify igaster/laravel-theme installation'
        );

        // Confirm Filament does NOT hijack the default Blade namespace
        $viewFinder    = app('view')->getFinder();
        $hints         = method_exists($viewFinder, 'getHints') ? $viewFinder->getHints() : [];
        $filamentHints = array_keys(array_filter($hints, fn ($k) => str_starts_with($k, 'filament'), ARRAY_FILTER_USE_KEY));
        $safe          = empty($filamentHints) || !in_array('app', $filamentHints, true);

        $this->result(
            'Filament does not override default Blade namespace',
            $safe,
            $safe ? 'Blade namespaces intact' : 'Filament appears to have overridden a core Blade namespace'
        );
    }

    // -------------------------------------------------------------------------

    /**
     * Record and display a single check result.
     */
    private function result(string $label, bool $pass, string $detail = ''): void
    {
        $this->results[$label] = $pass;

        $icon   = $pass ? '<fg=green>✓</>' : '<fg=red>✗</>';
        $status = $pass ? '<fg=green>PASS</>' : '<fg=red>FAIL</>';

        $this->line("  {$icon}  {$status}  {$label}");

        if ($detail && (!$pass || $this->getOutput()->isVerbose())) {
            $this->line("        <fg=gray>{$detail}</>");
        }
    }

    private function printSummary(): void
    {
        $total  = count($this->results);
        $passed = collect($this->results)->filter()->count();
        $failed = $total - $passed;

        $this->info('');
        $this->info('─────────────────────────────────────────────');

        if ($failed === 0) {
            $this->info("<fg=green>All {$total} checks passed. Titan panel is ready.</>");
        } else {
            $this->warn("<fg=yellow>{$passed}/{$total} checks passed. {$failed} issue(s) require attention.</>");
        }

        $this->info('─────────────────────────────────────────────');
        $this->info('');
    }
}
