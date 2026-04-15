<?php

namespace Tests\Unit\Modules;

use PHPUnit\Framework\TestCase;

class GToSModuleHealthChecksTest extends TestCase
{
    public function testModulesFromGToSWithModuleJsonHaveHealthChecksManifest(): void
    {
        $modulesPath = dirname(__DIR__, 3) . '/Modules';
        $moduleDirectories = array_filter(glob($modulesPath . '/*') ?: [], 'is_dir');

        foreach ($moduleDirectories as $moduleDirectory) {
            $moduleName = basename($moduleDirectory);
            $first = strtoupper(substr($moduleName, 0, 1));

            if ($first < 'G' || $first > 'S') {
                continue;
            }

            if (!is_file($moduleDirectory . '/module.json')) {
                continue;
            }

            $checksPath = $moduleDirectory . '/health/checks.php';
            $this->assertFileExists($checksPath, "Missing health/checks.php for module {$moduleName}");

            $checks = require $checksPath;
            $this->assertIsArray($checks, "health/checks.php must return an array for module {$moduleName}");
            $this->assertNotEmpty($checks, "health/checks.php must define checks for module {$moduleName}");
        }
    }
}
