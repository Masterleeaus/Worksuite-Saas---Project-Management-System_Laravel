<?php

namespace Tests\Unit;

use PHPUnit\Framework\TestCase;

class FsmModuleHealthChecksTest extends TestCase
{
    private string $repoRoot;

    protected function setUp(): void
    {
        parent::setUp();
        $this->repoRoot = dirname(__DIR__, 2);
    }

    public function test_fsm_vehicle_health_checks_are_not_placeholder(): void
    {
        $content = file_get_contents($this->repoRoot . '/Modules/FSMVehicle/health/checks.php');

        $this->assertStringNotContainsString('return [];', $content);
        $this->assertStringContainsString("'id'       => 'fsmvehicle:module_json'", $content);
        $this->assertStringContainsString("'id'       => 'fsmvehicle:service_provider'", $content);
        $this->assertStringContainsString("'id'       => 'fsmvehicle:migrations'", $content);
    }

    public function test_fsm_workflow_health_checks_include_core_structure_guards(): void
    {
        $content = file_get_contents($this->repoRoot . '/Modules/FSMWorkflow/health/checks.php');

        $this->assertStringContainsString("'id'       => 'fsmworkflow:module_json'", $content);
        $this->assertStringContainsString("'id'       => 'fsmworkflow:service_provider'", $content);
        $this->assertStringContainsString("'id'       => 'fsmworkflow:routes_web'", $content);
    }
}
