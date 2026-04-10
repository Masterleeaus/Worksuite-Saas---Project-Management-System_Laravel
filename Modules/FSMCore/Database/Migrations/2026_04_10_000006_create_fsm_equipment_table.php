<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('fsm_equipment', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('company_id')->nullable()->index();
            $table->unsignedBigInteger('location_id')->nullable()->index();
            $table->string('name', 256);
            $table->string('category', 128)->nullable();
            $table->text('notes')->nullable();
            $table->date('warranty_expiry')->nullable();
            $table->boolean('active')->default(true);
            $table->timestamps();

            $table->foreign('location_id')->references('id')->on('fsm_locations')->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('fsm_equipment');
    }
};
