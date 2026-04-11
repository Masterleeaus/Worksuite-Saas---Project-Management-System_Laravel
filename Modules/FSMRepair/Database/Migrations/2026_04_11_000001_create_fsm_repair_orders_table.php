<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

// fieldservice_repair: repair orders linked to FSM work orders
return new class extends Migration {
    public function up(): void
    {
        if (! Schema::hasTable('fsm_repair_orders')) {
            Schema::create('fsm_repair_orders', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('company_id')->nullable()->index();
                $table->string('name')->unique()->comment('Repair reference number');

                // Link back to the FSM work order
                $table->unsignedBigInteger('fsm_order_id')->nullable();
                $table->unsignedBigInteger('fsm_order_type_id')->nullable()
                      ->comment('internal_type: repair');

                // What is being repaired
                $table->unsignedBigInteger('product_id')->nullable();
                $table->string('lot_id')->nullable()->comment('Serial / lot number');
                $table->text('problem_description')->nullable();
                $table->text('repair_notes')->nullable();

                // Who
                $table->unsignedBigInteger('technician_id')->nullable()
                      ->comment('fsm_worker / employee');
                $table->unsignedBigInteger('partner_id')->nullable()
                      ->comment('customer');

                // Status
                $table->enum('state', ['draft','confirmed','under_repair','done','cancelled'])
                      ->default('draft');

                // Dates
                $table->timestamp('scheduled_date')->nullable();
                $table->timestamp('date_completed')->nullable();

                // Cost
                $table->decimal('parts_cost', 15, 2)->nullable();
                $table->decimal('labour_cost', 15, 2)->nullable();

                $table->unsignedBigInteger('created_by')->nullable();
                $table->timestamps();
                $table->softDeletes();

                if (Schema::hasTable('fsm_orders')) {
                    $table->foreign('fsm_order_id')->references('id')
                          ->on('fsm_orders')->nullOnDelete();
                }
            });
        }

        // Add repair_count counter to fsm_orders
        if (Schema::hasTable('fsm_orders') && ! Schema::hasColumn('fsm_orders', 'repair_count')) {
            Schema::table('fsm_orders', function (Blueprint $table) {
                $table->integer('repair_count')->default(0)->after('type_id');
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('fsm_repair_orders');

        if (Schema::hasTable('fsm_orders') && Schema::hasColumn('fsm_orders', 'repair_count')) {
            Schema::table('fsm_orders', function (Blueprint $table) {
                $table->dropColumn('repair_count');
            });
        }
    }
};
