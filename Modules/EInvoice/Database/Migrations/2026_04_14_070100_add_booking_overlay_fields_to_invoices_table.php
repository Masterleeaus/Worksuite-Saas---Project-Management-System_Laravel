<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('invoices')) {
            return;
        }

        Schema::table('invoices', function (Blueprint $table) {
            if (!Schema::hasColumn('invoices', 'booking_id')) {
                $table->unsignedBigInteger('booking_id')->nullable()->after('order_id');
                $table->index('booking_id');
            }

            if (!Schema::hasColumn('invoices', 'job_date')) {
                $table->date('job_date')->nullable()->after('due_date');
            }

            if (!Schema::hasColumn('invoices', 'service_address')) {
                $table->string('service_address')->nullable()->after('job_date');
            }

            if (!Schema::hasColumn('invoices', 'service_type')) {
                $table->string('service_type')->nullable()->after('service_address');
            }

            if (!Schema::hasColumn('invoices', 'auto_generated')) {
                $table->boolean('auto_generated')->default(false)->after('service_type');
            }
        });
    }

    public function down(): void
    {
        if (!Schema::hasTable('invoices')) {
            return;
        }

        Schema::table('invoices', function (Blueprint $table) {
            if (Schema::hasColumn('invoices', 'booking_id')) {
                try {
                    $table->dropIndex(['booking_id']);
                } catch (\Throwable) {
                    // Index may not exist in upgraded environments.
                }
            }

            $columns = array_filter([
                Schema::hasColumn('invoices', 'booking_id') ? 'booking_id' : null,
                Schema::hasColumn('invoices', 'job_date') ? 'job_date' : null,
                Schema::hasColumn('invoices', 'service_address') ? 'service_address' : null,
                Schema::hasColumn('invoices', 'service_type') ? 'service_type' : null,
                Schema::hasColumn('invoices', 'auto_generated') ? 'auto_generated' : null,
            ]);

            if (!empty($columns)) {
                $table->dropColumn($columns);
            }
        });
    }
};
