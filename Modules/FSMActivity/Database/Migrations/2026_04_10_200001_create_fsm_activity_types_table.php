<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('fsm_activity_types', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('company_id')->nullable()->index();
            $table->string('name', 128);
            $table->string('icon', 64)->nullable();
            $table->unsignedInteger('delay_count')->default(1);
            $table->string('delay_unit', 16)->default('days'); // days, weeks, months
            $table->unsignedBigInteger('default_user_id')->nullable()->index();
            $table->string('summary', 255)->nullable();
            $table->boolean('active')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('fsm_activity_types');
    }
};
