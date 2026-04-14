<?php

use App\Models\Permission;
use App\Models\Module;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    private array $permissionsToAdd = [
        [
            'name'                 => 'run_payroll',
            'display_name'        => 'Run Payroll',
            'allowed_permissions' => Permission::ALL_NONE,
        ],
        [
            'name'                 => 'approve_payroll',
            'display_name'        => 'Approve Payroll',
            'allowed_permissions' => Permission::ALL_NONE,
        ],
        [
            'name'                 => 'export_payroll',
            'display_name'        => 'Export Payroll',
            'allowed_permissions' => Permission::ALL_NONE,
        ],
    ];

    public function up(): void
    {
        $module = Module::firstOrCreate(['module_name' => 'payroll']);

        foreach ($this->permissionsToAdd as $permData) {
            Permission::firstOrCreate(
                ['name' => $permData['name'], 'module_id' => $module->id],
                $permData + ['module_id' => $module->id]
            );
        }
    }

    public function down(): void
    {
        $module = Module::where('module_name', 'payroll')->first();

        if (! $module) {
            return;
        }

        Permission::where('module_id', $module->id)
            ->whereIn('name', ['run_payroll', 'approve_payroll', 'export_payroll'])
            ->delete();
    }
};
