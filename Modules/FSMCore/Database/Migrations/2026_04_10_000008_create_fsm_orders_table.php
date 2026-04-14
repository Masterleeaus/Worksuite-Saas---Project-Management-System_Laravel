<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        if (Schema::hasTable('fsm_orders')) {
            return;
        }

        Schema::create('fsm_orders', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('company_id')->nullable()->index();
            $table->string('name', 64)->unique(); // auto-generated reference e.g. ORD-00001
            $table->unsignedBigInteger('location_id')->nullable()->index();
            $table->unsignedBigInteger('person_id')->nullable()->index();   // assigned worker (user)
            $table->unsignedBigInteger('team_id')->nullable()->index();
            $table->unsignedBigInteger('stage_id')->nullable()->index();
            $table->unsignedBigInteger('template_id')->nullable()->index();
            $table->string('priority', 2)->default('0'); // 0=normal, 1=urgent
            $table->integer('color')->default(0);
            $table->datetime('scheduled_date_start')->nullable();
            $table->datetime('scheduled_date_end')->nullable();
            $table->datetime('date_start')->nullable(); // actual check-in
            $table->datetime('date_end')->nullable();   // actual check-out
            $table->text('description')->nullable();
            $table->timestamps();

            if (Schema::hasTable('fsm_locations')) {
                $table->foreign('location_id')->references('id')->on('fsm_locations')->nullOnDelete();
            }
            if (Schema::hasTable('fsm_teams')) {
                $table->foreign('team_id')->references('id')->on('fsm_teams')->nullOnDelete();
            }
            if (Schema::hasTable('fsm_stages')) {
                $table->foreign('stage_id')->references('id')->on('fsm_stages')->nullOnDelete();
            }
            if (Schema::hasTable('fsm_templates')) {
                $table->foreign('template_id')->references('id')->on('fsm_templates')->nullOnDelete();
            }
        });

        Schema::create('fsm_order_equipment', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('fsm_order_id');
            $table->unsignedBigInteger('fsm_equipment_id');

            if (Schema::hasTable('fsm_orders')) {
                $table->foreign('fsm_order_id')->references('id')->on('fsm_orders')->cascadeOnDelete();
            }
            if (Schema::hasTable('fsm_equipment')) {
                $table->foreign('fsm_equipment_id')->references('id')->on('fsm_equipment')->cascadeOnDelete();
            }
        });

        Schema::create('fsm_order_tag', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('fsm_order_id');
            $table->unsignedBigInteger('fsm_tag_id');

            if (Schema::hasTable('fsm_orders')) {
                $table->foreign('fsm_order_id')->references('id')->on('fsm_orders')->cascadeOnDelete();
            }
            if (Schema::hasTable('fsm_tags')) {
                $table->foreign('fsm_tag_id')->references('id')->on('fsm_tags')->cascadeOnDelete();
            }
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('fsm_order_tag');
        Schema::dropIfExists('fsm_order_equipment');
        Schema::dropIfExists('fsm_orders');
    }
};
