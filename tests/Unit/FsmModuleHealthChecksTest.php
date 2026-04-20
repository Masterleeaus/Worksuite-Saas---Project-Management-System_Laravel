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

    public function test_fsm_activity_health_checks_are_defined(): void
    {
        $content = file_get_contents($this->repoRoot . '/Modules/FSMActivity/health/checks.php');

        $this->assertStringContainsString("'id'       => 'fsmactivity:module_json'", $content);
        $this->assertStringContainsString("'id'       => 'fsmactivity:service_provider'", $content);
        $this->assertStringContainsString("'id'       => 'fsmactivity:fsm_core_dep'", $content);
    }

    public function test_fsm_equipment_health_checks_are_defined(): void
    {
        $content = file_get_contents($this->repoRoot . '/Modules/FSMEquipment/health/checks.php');

        $this->assertStringContainsString("'id'       => 'fsmequipment:module_json'", $content);
        $this->assertStringContainsString("'id'       => 'fsmequipment:migrations'", $content);
        $this->assertStringContainsString("'id'       => 'fsmequipment:fsm_core_dep'", $content);
    }

    public function test_fsm_recurring_health_checks_are_defined(): void
    {
        $content = file_get_contents($this->repoRoot . '/Modules/FSMRecurring/health/checks.php');

        $this->assertStringContainsString("'id'       => 'fsmrecurring:module_json'", $content);
        $this->assertStringContainsString("'id'       => 'fsmrecurring:service_provider'", $content);
        $this->assertStringContainsString("'id'       => 'fsmrecurring:fsm_core_dep'", $content);
    }

    public function test_fsm_repair_health_checks_are_defined(): void
    {
        $content = file_get_contents($this->repoRoot . '/Modules/FSMRepair/health/checks.php');

        $this->assertStringContainsString("'id'       => 'fsmrepair:module_json'", $content);
        $this->assertStringContainsString("'id'       => 'fsmrepair:migrations'", $content);
        $this->assertStringContainsString("'id'       => 'fsmrepair:fsm_core_dep'", $content);
    }

    public function test_fsm_repair_template_health_checks_are_defined(): void
    {
        $content = file_get_contents($this->repoRoot . '/Modules/FSMRepairTemplate/health/checks.php');

        $this->assertStringContainsString("'id'       => 'fsmrepairtemplate:module_json'", $content);
        $this->assertStringContainsString("'id'       => 'fsmrepairtemplate:service_provider'", $content);
        $this->assertStringContainsString("'id'       => 'fsmrepairtemplate:fsm_repair_dep'", $content);
    }

    public function test_fsm_size_health_checks_are_defined(): void
    {
        $content = file_get_contents($this->repoRoot . '/Modules/FSMSize/health/checks.php');

        $this->assertStringContainsString("'id'       => 'fsmsize:module_json'", $content);
        $this->assertStringContainsString("'id'       => 'fsmsize:service_provider'", $content);
        $this->assertStringContainsString("'id'       => 'fsmsize:fsm_core_dep'", $content);
    }

    public function test_fsm_skill_health_checks_are_defined(): void
    {
        $content = file_get_contents($this->repoRoot . '/Modules/FSMSkill/health/checks.php');

        $this->assertStringContainsString("'id'       => 'fsmskill:module_json'", $content);
        $this->assertStringContainsString("'id'       => 'fsmskill:migrations'", $content);
        $this->assertStringContainsString("'id'       => 'fsmskill:fsm_core_dep'", $content);
    }

    public function test_fsm_stage_action_health_checks_are_defined(): void
    {
        $content = file_get_contents($this->repoRoot . '/Modules/FSMStageAction/health/checks.php');

        $this->assertStringContainsString("'id'       => 'fsmstageaction:module_json'", $content);
        $this->assertStringContainsString("'id'       => 'fsmstageaction:service_provider'", $content);
        $this->assertStringContainsString("'id'       => 'fsmstageaction:fsm_core_dep'", $content);
    }

    public function test_fsm_stock_health_checks_are_defined(): void
    {
        $content = file_get_contents($this->repoRoot . '/Modules/FSMStock/health/checks.php');

        $this->assertStringContainsString("'id'       => 'fsmstock:module_json'", $content);
        $this->assertStringContainsString("'id'       => 'fsmstock:migrations'", $content);
        $this->assertStringContainsString("'id'       => 'fsmstock:fsm_core_dep'", $content);
    }

    public function test_fsm_timesheet_health_checks_are_defined(): void
    {
        $content = file_get_contents($this->repoRoot . '/Modules/FSMTimesheet/health/checks.php');

        $this->assertStringContainsString("'id'       => 'fsmtimesheet:module_json'", $content);
        $this->assertStringContainsString("'id'       => 'fsmtimesheet:service_provider'", $content);
        $this->assertStringContainsString("'id'       => 'fsmtimesheet:fsm_core_dep'", $content);
    }

    public function test_fsm_route_availability_health_checks_are_defined(): void
    {
        $content = file_get_contents($this->repoRoot . '/Modules/FSMRouteAvailability/health/checks.php');

        $this->assertStringContainsString("'id'       => 'fsmrouteavailability:module_json'", $content);
        $this->assertStringContainsString("'id'       => 'fsmrouteavailability:service_provider'", $content);
        $this->assertStringContainsString("'id'       => 'fsmrouteavailability:routes_web'", $content);
        $this->assertStringContainsString("'id'       => 'fsmrouteavailability:migrations'", $content);
        $this->assertStringContainsString("'id'       => 'fsmrouteavailability:fsm_route_dep'", $content);
        $this->assertStringContainsString("'id'       => 'fsmrouteavailability:fsm_availability_dep'", $content);
    }

    public function test_fsm_sale_recurring_health_checks_are_defined(): void
    {
        $content = file_get_contents($this->repoRoot . '/Modules/FSMSaleRecurring/health/checks.php');

        $this->assertStringContainsString("'id'       => 'fsmsalerecurring:module_json'", $content);
        $this->assertStringContainsString("'id'       => 'fsmsalerecurring:service_provider'", $content);
        $this->assertStringContainsString("'id'       => 'fsmsalerecurring:routes_web'", $content);
        $this->assertStringContainsString("'id'       => 'fsmsalerecurring:migrations'", $content);
        $this->assertStringContainsString("'id'       => 'fsmsalerecurring:fsm_recurring_dep'", $content);
        $this->assertStringContainsString("'id'       => 'fsmsalerecurring:fsm_sales_dep'", $content);
    }
}
