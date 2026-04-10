<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

/**
 * Extend the core `tasks` table with FSM / cleaning-business fields.
 *
 * A Booking IS a Task:  task_type = 'booking' acts as the discriminator.
 * Existing tasks are unaffected because every new column has a default or is nullable.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::table('tasks', function (Blueprint $table) {
            // ── Discriminator ──────────────────────────────────────────────
            if (! Schema::hasColumn('tasks', 'task_type')) {
                $table->string('task_type')->default('task')->after('id')
                    ->comment('task | booking | inspection | quote_visit');
            }

            // ── Booking reference ──────────────────────────────────────────
            if (! Schema::hasColumn('tasks', 'booking_id')) {
                $table->unsignedBigInteger('booking_id')->nullable()->after('task_type')
                    ->comment('Human-readable booking sequence number');
            }

            // ── Service details ────────────────────────────────────────────
            if (! Schema::hasColumn('tasks', 'service_type')) {
                $table->string('service_type')->nullable()
                    ->comment('regular | deep_clean | end_of_lease | carpet | window');
            }

            if (! Schema::hasColumn('tasks', 'service_address')) {
                $table->string('service_address')->nullable();
            }

            if (! Schema::hasColumn('tasks', 'service_lat')) {
                $table->decimal('service_lat', 10, 7)->nullable();
            }

            if (! Schema::hasColumn('tasks', 'service_lng')) {
                $table->decimal('service_lng', 10, 7)->nullable();
            }

            // ── Property details ───────────────────────────────────────────
            if (! Schema::hasColumn('tasks', 'property_type')) {
                $table->string('property_type')->nullable()
                    ->comment('residential | commercial | strata');
            }

            if (! Schema::hasColumn('tasks', 'bedrooms')) {
                $table->integer('bedrooms')->nullable();
            }

            if (! Schema::hasColumn('tasks', 'bathrooms')) {
                $table->integer('bathrooms')->nullable();
            }

            // ── Recurrence ─────────────────────────────────────────────────
            if (! Schema::hasColumn('tasks', 'frequency')) {
                $table->string('frequency')->nullable()
                    ->comment('once | weekly | fortnightly | monthly');
            }

            if (! Schema::hasColumn('tasks', 'recurrence_parent_id')) {
                $table->unsignedBigInteger('recurrence_parent_id')->nullable()
                    ->comment('Self-referencing FK: child bookings point to their parent');
            }

            // ── Site access ────────────────────────────────────────────────
            if (! Schema::hasColumn('tasks', 'access_method')) {
                $table->string('access_method')->nullable()
                    ->comment('client_present | key | lockbox | alarm');
            }

            if (! Schema::hasColumn('tasks', 'alarm_code')) {
                // Value is stored encrypted by the CleaningBooking model cast.
                $table->text('alarm_code')->nullable()
                    ->comment('Encrypted alarm code — decrypt via CleaningBooking model cast');
            }

            if (! Schema::hasColumn('tasks', 'key_number')) {
                $table->string('key_number')->nullable();
            }

            // ── Timing ─────────────────────────────────────────────────────
            if (! Schema::hasColumn('tasks', 'estimated_duration_hours')) {
                $table->decimal('estimated_duration_hours', 4, 1)->nullable();
            }

            if (! Schema::hasColumn('tasks', 'actual_duration_hours')) {
                $table->decimal('actual_duration_hours', 4, 1)->nullable();
            }

            // ── Crew requirements ──────────────────────────────────────────
            if (! Schema::hasColumn('tasks', 'supplies_required')) {
                $table->boolean('supplies_required')->default(false);
            }

            if (! Schema::hasColumn('tasks', 'num_cleaners_required')) {
                $table->integer('num_cleaners_required')->default(1);
            }

            // ── FSM status ─────────────────────────────────────────────────
            if (! Schema::hasColumn('tasks', 'booking_status')) {
                $table->string('booking_status')->default('pending')
                    ->comment('pending | confirmed | en_route | in_progress | completed | cancelled | reclean');
            }

            if (! Schema::hasColumn('tasks', 'cleaner_arrived_at')) {
                $table->timestamp('cleaner_arrived_at')->nullable();
            }

            if (! Schema::hasColumn('tasks', 'cleaner_departed_at')) {
                $table->timestamp('cleaner_departed_at')->nullable();
            }

            // ── Invoice generation ─────────────────────────────────────────
            if (! Schema::hasColumn('tasks', 'invoice_generated')) {
                $table->boolean('invoice_generated')->default(false);
            }

            if (! Schema::hasColumn('tasks', 'generated_invoice_id')) {
                $table->unsignedBigInteger('generated_invoice_id')->nullable()
                    ->comment('FK to invoices.id — set once, prevents double-invoice');
            }
        });

        // Performance indexes — only add if the column was just created.
        Schema::table('tasks', function (Blueprint $table) {
            if (Schema::hasColumn('tasks', 'task_type') && ! $this->indexExists('tasks', 'tasks_task_type_index')) {
                $table->index('task_type', 'tasks_task_type_index');
            }

            if (Schema::hasColumn('tasks', 'booking_status') && ! $this->indexExists('tasks', 'tasks_booking_status_index')) {
                $table->index('booking_status', 'tasks_booking_status_index');
            }

            if (Schema::hasColumn('tasks', 'service_type') && ! $this->indexExists('tasks', 'tasks_service_type_index')) {
                $table->index('service_type', 'tasks_service_type_index');
            }

            if (Schema::hasColumn('tasks', 'frequency') && ! $this->indexExists('tasks', 'tasks_frequency_index')) {
                $table->index('frequency', 'tasks_frequency_index');
            }
        });
    }

    public function down(): void
    {
        Schema::table('tasks', function (Blueprint $table) {
            // Drop indexes first (ignore errors if they don't exist).
            $this->dropIndexSafe($table, 'tasks_task_type_index');
            $this->dropIndexSafe($table, 'tasks_booking_status_index');
            $this->dropIndexSafe($table, 'tasks_service_type_index');
            $this->dropIndexSafe($table, 'tasks_frequency_index');

            $columns = [
                'task_type', 'booking_id', 'service_type', 'service_address',
                'service_lat', 'service_lng', 'property_type', 'bedrooms', 'bathrooms',
                'frequency', 'recurrence_parent_id', 'access_method', 'alarm_code',
                'key_number', 'estimated_duration_hours', 'actual_duration_hours',
                'supplies_required', 'num_cleaners_required', 'booking_status',
                'cleaner_arrived_at', 'cleaner_departed_at',
                'invoice_generated', 'generated_invoice_id',
            ];

            $existing = array_filter($columns, fn ($c) => Schema::hasColumn('tasks', $c));

            if ($existing) {
                $table->dropColumn(array_values($existing));
            }
        });
    }

    /** Check whether a named index already exists on a table. */
    private function indexExists(string $table, string $index): bool
    {
        try {
            return collect(
                Schema::getConnection()->getDoctrineSchemaManager()->listTableIndexes($table)
            )->has($index);
        } catch (\Throwable) {
            // Doctrine not available — use raw query fallback.
            try {
                $indexes = \Illuminate\Support\Facades\DB::select(
                    "SHOW INDEX FROM `{$table}` WHERE Key_name = ?",
                    [$index]
                );
                return ! empty($indexes);
            } catch (\Throwable) {
                return false;
            }
        }
    }

    private function dropIndexSafe(Blueprint $table, string $index): void
    {
        try {
            $table->dropIndex($index);
        } catch (\Throwable) {
            // Index did not exist — harmless.
        }
    }
};
