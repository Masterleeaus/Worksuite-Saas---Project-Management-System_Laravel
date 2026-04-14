<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        $candidates = [
            'custom_modules',
            'module_settings',
            'package_modules',
            'packages_modules',
        ];

        foreach ($candidates as $table) {
            if (!Schema::hasTable($table)) {
                continue;
            }

            // Try to detect a "slug-like" column
            $slugCol = null;
            foreach (['alias','slug','module_name','name','key','module'] as $col) {
                if (Schema::hasColumn($table, $col)) { $slugCol = $col; break; }
            }

            if (!$slugCol) {
                continue;
            }

            $exists = DB::table($table)->where($slugCol, 'titandocs')->exists();
            if ($exists) {
                continue;
            }

            $row = [];
            // populate the slug col
            $row[$slugCol] = 'titandocs';

            // best-effort fill common columns
            foreach (['display_name','title','label','module_title'] as $col) {
                if (Schema::hasColumn($table, $col)) { $row[$col] = 'TitanDocs'; }
            }
            foreach (['description','details'] as $col) {
                if (Schema::hasColumn($table, $col)) { $row[$col] = 'AI-assisted document templates and documents.'; }
            }
            foreach (['is_active','active','enabled','status'] as $col) {
                if (Schema::hasColumn($table, $col)) { $row[$col] = 1; }
            }
            foreach (['created_at','updated_at'] as $col) {
                if (Schema::hasColumn($table, $col)) { $row[$col] = now(); }
            }

            try {
                DB::table($table)->insert($row);
            } catch (\Throwable $e) {
                // Swallow errors: registry schemas differ between WorkSuite forks.
                continue;
            }
        }
    }

    public function down(): void
    {
        // no-op (safe)
    }
};
