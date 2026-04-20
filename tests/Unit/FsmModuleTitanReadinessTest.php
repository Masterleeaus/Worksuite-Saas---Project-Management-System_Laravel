<?php

namespace Tests\Unit;

use PHPUnit\Framework\TestCase;

class FsmModuleTitanReadinessTest extends TestCase
{
    private string $repoRoot;

    protected function setUp(): void
    {
        parent::setUp();
        $this->repoRoot = dirname(__DIR__, 2);
    }

    public function test_fsm_modules_have_valid_nwidart_structure_contract(): void
    {
        foreach ($this->fsmModuleDirectories() as $moduleDirectory) {
            $moduleName = basename($moduleDirectory);
            $moduleJsonPath = $moduleDirectory . '/module.json';

            $this->assertFileExists($moduleJsonPath, "Missing module.json for {$moduleName}");

            $moduleJson = json_decode((string) file_get_contents($moduleJsonPath), true);
            $this->assertIsArray($moduleJson, "Invalid module.json for {$moduleName}");
            $this->assertSame($moduleName, $moduleJson['name'] ?? null, "module.json name mismatch for {$moduleName}");
            $this->assertNotEmpty($moduleJson['alias'] ?? null, "Missing alias in module.json for {$moduleName}");
            $this->assertMatchesRegularExpression('/^[a-z0-9]+$/', (string) ($moduleJson['alias'] ?? ''), "Alias must be lowercase alphanumeric for {$moduleName}");
            $this->assertIsArray($moduleJson['providers'] ?? null, "Missing providers array for {$moduleName}");
            $this->assertNotEmpty($moduleJson['providers'] ?? [], "Providers array cannot be empty for {$moduleName}");

            $this->assertFileExists($moduleDirectory . '/Config/config.php', "Missing Config/config.php for {$moduleName}");
            $this->assertFileExists($moduleDirectory . '/Providers/RouteServiceProvider.php', "Missing Providers/RouteServiceProvider.php for {$moduleName}");
            $this->assertFileExists($moduleDirectory . '/Routes/web.php', "Missing Routes/web.php for {$moduleName}");
            $this->assertFileExists($moduleDirectory . '/health/checks.php', "Missing health/checks.php for {$moduleName}");

            foreach ($moduleJson['providers'] as $providerClass) {
                $this->assertIsString($providerClass, "Provider class must be a string in {$moduleName}");
                $providerPath = $this->repoRoot . '/' . str_replace('\\', '/', $providerClass) . '.php';
                $this->assertFileExists($providerPath, "Provider file not found: {$providerClass}");
            }
        }
    }

    public function test_fsm_modules_with_api_routes_expose_api_named_routes(): void
    {
        foreach ($this->fsmModuleDirectories() as $moduleDirectory) {
            $moduleName = basename($moduleDirectory);
            $apiRoutesPath = $moduleDirectory . '/Routes/api.php';

            if (!is_file($apiRoutesPath)) {
                continue;
            }

            $routeProviderContent = (string) file_get_contents($moduleDirectory . '/Providers/RouteServiceProvider.php');
            $apiRouteContent = (string) file_get_contents($apiRoutesPath);

            $this->assertStringContainsString('api.php', $routeProviderContent, "RouteServiceProvider does not map Routes/api.php for {$moduleName}");
            $this->assertMatchesRegularExpression("/->name\\('(?:api\\.|fsm\\.api\\.)[a-z0-9_.-]*'\\)/", $apiRouteContent, "Routes/api.php should use api.* or fsm.api.* route names for {$moduleName}");
        }
    }

    public function test_fsm_routes_reference_existing_controller_classes(): void
    {
        foreach ($this->fsmModuleDirectories() as $moduleDirectory) {
            foreach (['web', 'api'] as $routeType) {
                $routePath = "{$moduleDirectory}/Routes/{$routeType}.php";
                if (!is_file($routePath)) {
                    continue;
                }

                $routeContents = (string) file_get_contents($routePath);
                preg_match_all('/use\\s+([A-Za-z0-9_\\\\]+Controller);/', $routeContents, $matches);
                $imports = $matches[1] ?? [];

                foreach ($imports as $importedController) {
                    $controllerPath = $this->repoRoot . '/' . str_replace('\\', '/', $importedController) . '.php';
                    $this->assertFileExists($controllerPath, "Route file {$routePath} imports missing controller {$importedController}");
                }
            }
        }
    }

    /**
     * @return array<int, string>
     */
    private function fsmModuleDirectories(): array
    {
        $paths = glob($this->repoRoot . '/Modules/FSM*') ?: [];

        return array_values(array_filter($paths, static fn (string $path) => is_dir($path)));
    }
}
