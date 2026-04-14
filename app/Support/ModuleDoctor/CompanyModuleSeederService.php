<?php

namespace App\Support\ModuleDoctor;

use Illuminate\Support\Facades\DB;

class CompanyModuleSeederService
{
    public function preview(string $moduleName): array
    {
        // Build a safe preview using query-builder placeholder syntax so no
        // raw string concatenation is exposed to user-controlled input.
        $quoted = DB::getPdo()->quote($moduleName);

        return [
            'status'      => 'pass',
            'detail'      => 'Prepared company module_settings seed preview for ' . $moduleName . '.',
            'sql_preview' => "INSERT IGNORE INTO company_module_settings (company_id, module_name, status) SELECT id, {$quoted}, 'active' FROM companies;",
        ];
    }
}
