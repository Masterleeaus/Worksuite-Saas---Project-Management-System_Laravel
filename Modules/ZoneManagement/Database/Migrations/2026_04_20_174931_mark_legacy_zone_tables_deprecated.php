<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        $tables = [
            'zone_check_ins',
            'cleaner_locations',
            'route_points',
            'zone_pricing',
            'zone_providers',
        ];

        $driver = Schema::getConnection()->getDriverName();
        if (!in_array($driver, ['mysql', 'mariadb'], true)) {
            return;
        }

        foreach ($tables as $table) {
            if (!Schema::hasTable($table)) {
                continue;
            }

            DB::statement("ALTER TABLE `{$table}` COMMENT = 'DEPRECATED: ZoneManagement compatibility table; canonical ownership moved to TitanGo/ProviderManagement/BookingModule.'");
        }
    }

    public function down(): void
    {
        // No rollback comment reset required.
    }
};

