<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('fsm_equipment_warranties', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('company_id')->nullable()->index();
            $table->unsignedBigInteger('equipment_id')->index();
            $table->date('warranty_start');
            $table->date('warranty_end');
            $table->string('supplier', 256)->nullable();
            $table->string('warranty_number', 128)->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();

            $table->foreign('equipment_id')->references('id')->on('fsm_equipment')->cascadeOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('fsm_equipment_warranties');
    }
};
