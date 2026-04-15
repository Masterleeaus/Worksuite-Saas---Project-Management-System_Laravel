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

    public function test_fsm_account_health_checks_are_defined(): void
    {
        $content = file_get_contents($this->repoRoot . '/Modules/FSMAccount/health/checks.php');

        $this->assertStringContainsString("'id'       => 'fsmaccount:module_json'", $content);
        $this->assertStringContainsString("'id'       => 'fsmaccount:service_provider'", $content);
        $this->assertStringContainsString("'id'       => 'fsmaccount:fsm_core_dep'", $content);
    }

    public function test_fsm_kanban_health_checks_are_defined(): void
    {
        $content = file_get_contents($this->repoRoot . '/Modules/FSMKanban/health/checks.php');

        $this->assertStringContainsString("'id'       => 'fsmkanban:module_json'", $content);
        $this->assertStringContainsString("'id'       => 'fsmkanban:migrations'", $content);
        $this->assertStringContainsString("'id'       => 'fsmkanban:fsm_core_dep'", $content);
    }

    public function test_fsm_project_health_checks_are_defined(): void
    {
        $content = file_get_contents($this->repoRoot . '/Modules/FSMProject/health/checks.php');

        $this->assertStringContainsString("'id'       => 'fsmproject:module_json'", $content);
        $this->assertStringContainsString("'id'       => 'fsmproject:service_provider'", $content);
        $this->assertStringContainsString("'id'       => 'fsmproject:fsm_core_dep'", $content);
    }
}
