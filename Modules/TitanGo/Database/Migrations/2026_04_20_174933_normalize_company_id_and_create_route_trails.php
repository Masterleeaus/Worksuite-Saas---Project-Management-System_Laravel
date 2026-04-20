<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        $driver = Schema::getConnection()->getDriverName();
        $tables = [
            'titan_go_location_pings',
            'titan_go_issues',
            'titan_go_site_notes',
            'titan_go_worker_statuses',
            'nexus_job_notes',
            'titan_go_checklist_completions',
        ];

        if ($driver !== 'sqlite') {
            foreach ($tables as $tableName) {
                if (!Schema::hasTable($tableName) || !Schema::hasColumn($tableName, 'company_id')) {
                    continue;
                }

                Schema::table($tableName, function (Blueprint $table) {
                    $table->unsignedBigInteger('company_id')->nullable()->change();
                });
            }
        }

        if (!Schema::hasTable('titan_go_route_trails')) {
            Schema::create('titan_go_route_trails', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('company_id')->nullable()->index();
                $table->unsignedInteger('worker_id')->index();
                $table->unsignedBigInteger('visit_id')->nullable()->index();
                $table->string('booking_id')->nullable()->index();
                $table->decimal('latitude', 10, 7);
                $table->decimal('longitude', 11, 7);
                $table->float('accuracy')->nullable();
                $table->float('speed')->nullable();
                $table->float('heading')->nullable();
                $table->unsignedInteger('sequence')->default(0);
                $table->timestamp('recorded_at')->nullable()->index();
                $table->timestamps();
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('titan_go_route_trails');
    }
};

