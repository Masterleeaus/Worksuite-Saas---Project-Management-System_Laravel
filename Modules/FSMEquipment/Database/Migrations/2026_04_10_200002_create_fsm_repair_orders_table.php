<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('fsm_repair_orders', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('company_id')->nullable()->index();
            $table->string('name', 256);
            $table->unsignedBigInteger('equipment_id')->nullable()->index();
            $table->unsignedBigInteger('fsm_location_id')->nullable()->index();
            $table->unsignedBigInteger('template_id')->nullable()->index();
            $table->unsignedBigInteger('fsm_order_id')->nullable()->index();
            $table->text('description')->nullable();
            $table->unsignedBigInteger('reported_by')->nullable()->index();
            $table->unsignedBigInteger('assigned_to')->nullable()->index();
            $table->enum('priority', ['low', 'normal', 'urgent'])->default('normal');
            $table->timestamp('date_reported')->nullable();
            $table->timestamp('date_scheduled')->nullable();
            $table->timestamp('date_completed')->nullable();
            $table->enum('stage', ['new', 'in_progress', 'awaiting_parts', 'completed', 'cancelled'])->default('new');
            $table->text('root_cause')->nullable();
            $table->decimal('cost', 12, 2)->nullable();
            $table->text('parts_used')->nullable();
            $table->boolean('under_warranty')->default(false);
            $table->timestamps();

            $table->foreign('equipment_id')->references('id')->on('fsm_equipment')->nullOnDelete();
            $table->foreign('fsm_location_id')->references('id')->on('fsm_locations')->nullOnDelete();
            $table->foreign('template_id')->references('id')->on('fsm_repair_order_templates')->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('fsm_repair_orders');
    }
};
