<?php

namespace App\Support\ModuleDoctor;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class ExtensionsRegistryTruthService
{
    public function inspect(string $moduleName): array
    {
        if (!Schema::hasTable('modules')) {
            return ['status' => 'warn', 'detail' => 'modules table not found.', 'rows' => []];
        }

        $rows = DB::table('modules')
            ->where('module_name', $moduleName)
            ->get()
            ->map(fn ($row) => (array) $row)
            ->all();

        return [
            'status' => count($rows) ? 'pass' : 'warn',
            'detail' => count($rows) ? 'Worksuite module table rows found for this module.' : 'No Worksuite module table row was found for this module.',
            'rows' => $rows,
            'ready' => count($rows) > 0,
        ];
    }
}
