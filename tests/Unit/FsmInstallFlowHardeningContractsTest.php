<?php

namespace Tests\Unit;

use PHPUnit\Framework\TestCase;

class FsmInstallFlowHardeningContractsTest extends TestCase
{
    private string $repoRoot;

    protected function setUp(): void
    {
        parent::setUp();
        $this->repoRoot = dirname(__DIR__, 2);
    }

    public function test_fsm_hardening_migration_covers_registry_package_module_settings_and_permissions(): void
    {
        $migrationPath = $this->repoRoot . '/Modules/FSMCore/Database/Migrations/2026_04_20_114200_harden_fsm_module_registry_and_module_settings.php';
        $this->assertFileExists($migrationPath);

        $contents = (string) file_get_contents($migrationPath);

        $this->assertStringContainsString('$this->syncModuleRegistry($modules);', $contents);
        $this->assertStringContainsString('$this->syncPackageEntitlements($modules);', $contents);
        $this->assertStringContainsString('$this->seedModuleSettings($modules);', $contents);
        $this->assertStringContainsString('$this->seedPermissions($modules);', $contents);

        $this->assertStringContainsString("Schema::hasTable('modules')", $contents);
        $this->assertStringContainsString("Schema::hasTable('packages')", $contents);
        $this->assertStringContainsString("Schema::hasTable('module_settings')", $contents);
        $this->assertStringContainsString("Schema::hasTable('permissions')", $contents);
        $this->assertStringContainsString("Schema::hasTable('permission_role')", $contents);
        $this->assertStringContainsString("Schema::hasTable('user_permissions')", $contents);

        $this->assertStringContainsString("'module_in_package'", $contents);
        $this->assertStringContainsString("'is_allowed'", $contents);
        $this->assertStringContainsString("'deactive'", $contents);
    }

    public function test_fsm_territory_render_targets_exist_for_region_district_branch_and_territory_flows(): void
    {
        $viewPaths = [
            'regions/index.blade.php',
            'regions/create.blade.php',
            'regions/edit.blade.php',
            'districts/index.blade.php',
            'districts/create.blade.php',
            'districts/edit.blade.php',
            'branches/index.blade.php',
            'branches/create.blade.php',
            'branches/edit.blade.php',
            'territories/index.blade.php',
            'territories/create.blade.php',
            'territories/edit.blade.php',
        ];

        foreach ($viewPaths as $relativeViewPath) {
            $absolutePath = $this->repoRoot . '/Modules/FSMTerritory/Resources/views/' . $relativeViewPath;
            $this->assertFileExists($absolutePath, "Missing FSMTerritory render target {$relativeViewPath}");
        }
    }
}
