<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

// fieldservice_repair_order_template: link repair templates to FSM templates/orders
return new class extends Migration {
    public function up(): void
    {
        // Add repair_order_template_id to fsm_templates
        if (Schema::hasTable('fsm_templates') && ! Schema::hasColumn('fsm_templates', 'repair_order_template_id')) {
            Schema::table('fsm_templates', function (Blueprint $table) {
                $table->unsignedBigInteger('repair_order_template_id')->nullable()->after('stage_id');
            });
        }

        // Add repair_order_template_id to fsm_orders (override from template)
        if (Schema::hasTable('fsm_orders') && ! Schema::hasColumn('fsm_orders', 'repair_order_template_id')) {
            Schema::table('fsm_orders', function (Blueprint $table) {
                $table->unsignedBigInteger('repair_order_template_id')->nullable()->after('template_id');
            });
        }

        // Repair order templates table
        if (! Schema::hasTable('fsm_repair_order_templates')) {
            Schema::create('fsm_repair_order_templates', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('company_id')->nullable()->index();
                $table->string('name');
                $table->text('instructions')->nullable();
                $table->unsignedBigInteger('type_id')->nullable()
                      ->comment('fsm_order_type.internal_type = repair');
                $table->boolean('active')->default(true);
                $table->timestamps();
                $table->softDeletes();
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('fsm_repair_order_templates');

        foreach (['fsm_templates', 'fsm_orders'] as $tbl) {
            if (Schema::hasTable($tbl) && Schema::hasColumn($tbl, 'repair_order_template_id')) {
                Schema::table($tbl, function (Blueprint $table) {
                    $table->dropColumn('repair_order_template_id');
                });
            }
        }
    }
};
