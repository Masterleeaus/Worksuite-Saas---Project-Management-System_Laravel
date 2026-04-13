<?php

use App\Models\Permission;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    private array $permissionsToAdd = [
        [
            'name'                 => 'run_payroll',
            'display_name'        => 'Run Payroll',
            'allowed_permissions' => Permission::ALL_NONE,
            'module_name'         => 'payroll',
        ],
        [
            'name'                 => 'approve_payroll',
            'display_name'        => 'Approve Payroll',
            'allowed_permissions' => Permission::ALL_NONE,
            'module_name'         => 'payroll',
        ],
        [
            'name'                 => 'export_payroll',
            'display_name'        => 'Export Payroll',
            'allowed_permissions' => Permission::ALL_NONE,
            'module_name'         => 'payroll',
        ],
    ];

    public function up(): void
    {
        foreach ($this->permissionsToAdd as $permData) {
            Permission::firstOrCreate(
                ['name' => $permData['name']],
                $permData
            );
        }
    }

    public function down(): void
    {
        Permission::whereIn('name', ['run_payroll', 'approve_payroll', 'export_payroll'])
            ->delete();
    }
};
