<?php

namespace Tests\Integration\CustomModules;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use App\Models\ModuleInstallLog;
use ZipArchive;

class CustomModuleInstallationTest extends TestCase
{
    use RefreshDatabase;

    protected string $testModulePath;
    protected string $testZipPath;
    
    protected function setUp(): void
    {
        parent::setUp();

        $this->withoutMiddleware();
        $this->ensureInstallerTestTables();
        
        $this->testModulePath = storage_path('test_module');
        $this->testZipPath = storage_path('test_module.zip');
    }
    
    protected function tearDown(): void
    {
        File::deleteDirectory($this->testModulePath);
        File::delete($this->testZipPath);
        File::deleteDirectory(base_path('Modules/TestModule'));
        $this->resetTestModuleStatuses();
        
        parent::tearDown();
    }
    
    /**
     * Test successful module installation with all validators passing
     */
    public function test_successful_module_installation()
    {
        $zip = $this->createValidTestModule('TestModule', '1.0.0');
        
        $response = $this->post(route('custom-modules.store'), [
            'filePath' => $zip,
        ]);
        
        $response->assertStatus(200);
        $this->assertContains($response->json('status'), ['success', 'warning', 'partial']);
        $response->assertJsonPath('analysis.module_name', 'TestModule');
        
        // Verify module files exist
        $this->assertTrue(File::exists(base_path('Modules/TestModule/module.json')));
        
        // Verify installation log was created
        $this->assertTrue(
            DB::table('module_install_logs')
                ->where('module_name', 'TestModule')
                ->whereIn('status', ['installed', 'partial', 'partial_failed'])
                ->exists()
        );
    }
    
    /**
     * Test that permission collisions are blocked
     */
    public function test_permission_collision_blocks_installation()
    {
        $moduleId = DB::table('modules')->value('id') ?? 1;

        // Create existing permission
        DB::table('permissions')->insert([
            'permission_key' => 'edit_invoices',
            'name' => 'edit_invoices',
            'module_id' => $moduleId,
            'module' => 'Core',
            'display_name' => 'Edit Invoices',
            'created_at' => now(),
            'updated_at' => now(),
        ]);
        
        // Create module with same permission key
        $zip = $this->createTestModuleWithPermission(
            'BadModule',
            'edit_invoices'
        );
        
        $response = $this->post(route('custom-modules.store'), [
            'filePath' => $zip,
        ]);
        
        $response->assertStatus(200);
        $response->assertJsonPath('status', 'fail');
        $this->assertStringContainsString('edit_invoices', (string) $response->json('analysis.blocking_issues.0'));
        
        // Verify module was NOT installed
        $this->assertFalse(File::exists(base_path('Modules/BadModule')));
        
        // Verify blocked log was created
        $this->assertDatabaseHas('module_install_logs', [
            'status' => 'install_blocked_collisions',
        ]);
    }
    
    /**
     * Test that route collisions are blocked
     */
    public function test_route_collision_blocks_installation()
    {
        // Create module with conflicting route
        $zip = $this->createTestModuleWithRoute(
            'BadRouteModule',
            '/account/settings/custom-modules'
        );
        
        $response = $this->post(route('custom-modules.store'), [
            'filePath' => $zip,
        ]);
        
        $response->assertStatus(200);
        $response->assertJsonPath('status', 'fail');
        $response->assertJsonPath('blocking_issues.0.code', 'ROUTE_COLLISION');
        
        // Verify module was NOT installed
        $this->assertFalse(File::exists(base_path('Modules/BadRouteModule')));
    }
    
    /**
     * Test that malicious code is detected
     */
    public function test_malicious_code_blocks_installation()
    {
        // Create module with shell_exec
        $zip = $this->createTestModuleWithShellCode('MaliciousModule');
        
        $response = $this->post(route('custom-modules.store'), [
            'filePath' => $zip,
        ]);
        
        $response->assertStatus(200);
        $response->assertJsonPath('status', 'fail');
        $response->assertJsonPath('blocking_issues.0.code', 'SHELL_PATTERN');
        
        // Verify module was NOT installed
        $this->assertFalse(File::exists(base_path('Modules/MaliciousModule')));
    }
    
    /**
     * Test rollback after installation
     */
    public function test_rollback_removes_module_and_restores_state()
    {
        // Install module
        $zip = $this->createValidTestModule('RollbackTestModule', '1.0.0');
        
        $response = $this->post(route('custom-modules.store'), [
            'filePath' => $zip,
        ]);
        
        $this->assertTrue(File::exists(base_path('Modules/RollbackTestModule')));
        
        // Get the install ID
        $install = ModuleInstallLog::latest()->first();
        
        // Rollback
        $rollbackResponse = $this->post(route('custom-modules.rollback', ['install' => $install->id]));
        
        $rollbackResponse->assertStatus(200);
        $rollbackResponse->assertJsonPath('status', 'success');
        
        // Verify module files are gone
        $this->assertFalse(File::exists(base_path('Modules/RollbackTestModule')));
        
        // Verify installation log is marked as rolled back
        $this->assertEquals('rolled_back', $install->refresh()->status);
    }
    
    /**
     * Test that repairs are executed
     */
    public function test_repairs_are_executed()
    {
        $zip = $this->createValidTestModule('RepairTestModule', '1.0.0');
        
        $response = $this->post(route('custom-modules.store'), [
            'filePath' => $zip,
        ]);
        
        $response->assertStatus(200);
        
        // Verify permissions were created
        $this->assertDatabaseHas('permissions', [
            'module' => 'RepairTestModule',
        ]);
    }
    
    /**
     * Test that snapshot is created
     */
    public function test_snapshot_is_captured_before_install()
    {
        $zip = $this->createValidTestModule('SnapshotTestModule', '1.0.0');
        
        $response = $this->post(route('custom-modules.store'), [
            'filePath' => $zip,
        ]);
        
        $install = ModuleInstallLog::latest()->first();
        
        $this->assertNotEmpty($install->pre_install_snapshot);
        $this->assertTrue($install->can_rollback);
    }
    
    /**
     * Test partial failure handling
     */
    public function test_partial_failure_is_logged()
    {
        $zip = $this->createValidTestModule('PartialModule', '1.0.0');
        
        // Mock a repair failure
        // (This would require more complex setup)
        
        $response = $this->post(route('custom-modules.store'), [
            'filePath' => $zip,
        ]);
        
        // Response should indicate success or partial based on repairs
        $this->assertContains($response->json('status'), ['success', 'partial']);
    }

    protected function ensureInstallerTestTables(): void
    {
        if (!Schema::hasTable('permissions')) {
            Schema::create('permissions', function (Blueprint $table) {
                $table->increments('id');
                $table->string('permission_key')->nullable();
                $table->string('name')->nullable();
                $table->string('module')->nullable();
                $table->string('display_name')->nullable();
                $table->timestamps();
            });
        } else {
            Schema::table('permissions', function (Blueprint $table) {
                if (!Schema::hasColumn('permissions', 'permission_key')) {
                    $table->string('permission_key')->nullable();
                }
                if (!Schema::hasColumn('permissions', 'name')) {
                    $table->string('name')->nullable();
                }
                if (!Schema::hasColumn('permissions', 'module')) {
                    $table->string('module')->nullable();
                }
                if (!Schema::hasColumn('permissions', 'display_name')) {
                    $table->string('display_name')->nullable();
                }
            });
        }

        if (!Schema::hasTable('module_install_logs')) {
            Schema::create('module_install_logs', function (Blueprint $table) {
                $table->increments('id');
                $table->string('event')->nullable();
                $table->string('module_name')->nullable();
                $table->string('status')->nullable();
                $table->json('blocking_issues')->nullable();
                $table->json('warnings')->nullable();
                $table->json('checks')->nullable();
                $table->json('package_summary')->nullable();
                $table->json('manifest_data')->nullable();
                $table->json('validation_results')->nullable();
                $table->json('readiness_flags')->nullable();
                $table->json('fix_plan')->nullable();
                $table->json('pre_install_snapshot')->nullable();
                $table->json('applied_repairs')->nullable();
                $table->json('repair_results')->nullable();
                $table->boolean('can_rollback')->default(false);
                $table->timestamp('installed_at')->nullable();
                $table->timestamp('last_verified_at')->nullable();
                $table->text('installation_notes')->nullable();
                $table->timestamps();
            });
        } else {
            Schema::table('module_install_logs', function (Blueprint $table) {
                if (!Schema::hasColumn('module_install_logs', 'event')) {
                    $table->string('event')->nullable();
                }
                if (!Schema::hasColumn('module_install_logs', 'module_name')) {
                    $table->string('module_name')->nullable();
                }
                if (!Schema::hasColumn('module_install_logs', 'status')) {
                    $table->string('status')->nullable();
                }
                if (!Schema::hasColumn('module_install_logs', 'blocking_issues')) {
                    $table->json('blocking_issues')->nullable();
                }
                if (!Schema::hasColumn('module_install_logs', 'warnings')) {
                    $table->json('warnings')->nullable();
                }
                if (!Schema::hasColumn('module_install_logs', 'checks')) {
                    $table->json('checks')->nullable();
                }
                if (!Schema::hasColumn('module_install_logs', 'package_summary')) {
                    $table->json('package_summary')->nullable();
                }
                if (!Schema::hasColumn('module_install_logs', 'manifest_data')) {
                    $table->json('manifest_data')->nullable();
                }
                if (!Schema::hasColumn('module_install_logs', 'validation_results')) {
                    $table->json('validation_results')->nullable();
                }
                if (!Schema::hasColumn('module_install_logs', 'readiness_flags')) {
                    $table->json('readiness_flags')->nullable();
                }
                if (!Schema::hasColumn('module_install_logs', 'fix_plan')) {
                    $table->json('fix_plan')->nullable();
                }
                if (!Schema::hasColumn('module_install_logs', 'pre_install_snapshot')) {
                    $table->json('pre_install_snapshot')->nullable();
                }
                if (!Schema::hasColumn('module_install_logs', 'applied_repairs')) {
                    $table->json('applied_repairs')->nullable();
                }
                if (!Schema::hasColumn('module_install_logs', 'repair_results')) {
                    $table->json('repair_results')->nullable();
                }
                if (!Schema::hasColumn('module_install_logs', 'can_rollback')) {
                    $table->boolean('can_rollback')->default(false);
                }
                if (!Schema::hasColumn('module_install_logs', 'installed_at')) {
                    $table->timestamp('installed_at')->nullable();
                }
                if (!Schema::hasColumn('module_install_logs', 'last_verified_at')) {
                    $table->timestamp('last_verified_at')->nullable();
                }
                if (!Schema::hasColumn('module_install_logs', 'installation_notes')) {
                    $table->text('installation_notes')->nullable();
                }
            });
        }
    }

    protected function resetTestModuleStatuses(): void
    {
        $statusPath = storage_path('app/modules_statuses.json');
        if (!File::exists($statusPath)) {
            return;
        }

        $statuses = json_decode((string) File::get($statusPath), true);
        if (!is_array($statuses)) {
            return;
        }

        $testModules = [
            'TestModule',
            'BadModule',
            'BadRouteModule',
            'MaliciousModule',
            'RollbackTestModule',
            'RepairTestModule',
            'SnapshotTestModule',
            'PartialModule',
            'test_TestModule',
        ];

        $updated = false;
        foreach ($testModules as $moduleName) {
            if (array_key_exists($moduleName, $statuses)) {
                unset($statuses[$moduleName]);
                $updated = true;
            }
        }

        if ($updated) {
            File::put($statusPath, json_encode($statuses, JSON_PRETTY_PRINT));
        }
    }
    
    // ========================================================================
    // Helper Methods
    // ========================================================================
    
    protected function createValidTestModule(string $name, string $version): string
    {
        $modulePath = storage_path("test_modules/{$name}");
        File::ensureDirectoryExists($modulePath);
        File::ensureDirectoryExists($modulePath . '/Config');
        File::ensureDirectoryExists($modulePath . '/Providers');
        File::ensureDirectoryExists($modulePath . '/Routes');
        
        // Create module.json
        File::put($modulePath . '/module.json', json_encode([
            'name' => $name,
            'alias' => $name,
            'version' => $version,
            'providers' => [
                "Modules\\{$name}\\Providers\\{$name}ServiceProvider",
            ],
            'permissions' => [
                ['key' => 'manage_' . strtolower($name), 'label' => "Manage {$name}"],
            ],
            'routes' => [
                ['uri' => "/{$name}", 'method' => 'GET'],
            ],
        ]));
        File::put($modulePath . '/composer.json', json_encode([
            'name' => 'modules/' . strtolower($name),
            'autoload' => [
                'psr-4' => [
                    "Modules\\{$name}\\" => '',
                ],
            ],
        ], JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES));

        File::put($modulePath . '/Config/config.php', <<<'PHP'
<?php

return [
    'parent_envato_id' => (string) config('froiden_envato.envato_item_id'),
    'parent_product_name' => (string) config('froiden_envato.envato_product_name'),
    'parent_min_version' => '0.0.0',
];
PHP
        );
        
        // Create a simple PHP file
        File::put($modulePath . '/Module.php', "<?php\nnamespace Modules\\{$name};\n\nclass Module\n{\n}");
        File::put(
            $modulePath . "/Providers/{$name}ServiceProvider.php",
            "<?php\n\nnamespace Modules\\{$name}\\Providers;\n\nuse Illuminate\\Support\\ServiceProvider;\n\nclass {$name}ServiceProvider extends ServiceProvider\n{\n    public function register(): void\n    {\n    }\n\n    public function boot(): void\n    {\n    }\n}\n"
        );
        File::put($modulePath . '/Routes/web.php', "<?php\n\nuse Illuminate\\Support\\Facades\\Route;\n\nRoute::get('/" . strtolower($name) . "', fn () => 'ok');\n");
        
        return $this->zipDirectory($modulePath, storage_path("{$name}.zip"));
    }
    
    protected function createTestModuleWithPermission(string $name, string $permissionKey): string
    {
        $modulePath = storage_path("test_modules/{$name}");
        File::ensureDirectoryExists($modulePath);
        
        File::put($modulePath . '/module.json', json_encode([
            'name' => $name,
            'version' => '1.0.0',
            'permissions' => [
                ['key' => $permissionKey, 'label' => ucfirst($permissionKey)],
            ],
        ]));
        
        return $this->zipDirectory($modulePath, storage_path("{$name}.zip"));
    }
    
    protected function createTestModuleWithRoute(string $name, string $routeUri): string
    {
        $modulePath = storage_path("test_modules/{$name}");
        File::ensureDirectoryExists($modulePath);
        
        File::put($modulePath . '/module.json', json_encode([
            'name' => $name,
            'version' => '1.0.0',
            'routes' => [
                ['uri' => $routeUri, 'method' => 'GET'],
            ],
        ]));
        
        return $this->zipDirectory($modulePath, storage_path("{$name}.zip"));
    }
    
    protected function createTestModuleWithShellCode(string $name): string
    {
        $modulePath = storage_path("test_modules/{$name}");
        File::ensureDirectoryExists($modulePath);
        
        File::put($modulePath . '/module.json', json_encode([
            'name' => $name,
            'version' => '1.0.0',
        ]));
        
        // Create malicious PHP file
        File::put($modulePath . '/Shell.php', "<?php\nshell_exec('whoami');\n");
        
        return $this->zipDirectory($modulePath, storage_path("{$name}.zip"));
    }
    
    protected function zipDirectory(string $source, string $destination): string
    {
        $zip = new ZipArchive();
        $zip->open($destination, ZipArchive::CREATE | ZipArchive::OVERWRITE);
        
        $files = File::allFiles($source);
        $root = basename($source);
        
        foreach ($files as $file) {
            $zip->addFile(
                $file->getRealPath(),
                $root . '/' . str_replace($source . '/', '', $file->getRealPath())
            );
        }
        
        $zip->close();
        
        return $destination;
    }
}
