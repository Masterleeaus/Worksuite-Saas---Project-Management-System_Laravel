<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('appointment_staff_capacities')) {
            Schema::create('appointment_staff_capacities', function (Blueprint $table) {
                $table->id();
            $table->unsignedBigInteger('company_id')->nullable()->index();
                $table->unsignedBigInteger('user_id');
                $table->unsignedInteger('max_per_day')->nullable();
                $table->unsignedInteger('max_per_slot')->nullable();
                $table->boolean('enforce_conflicts')->default(true);
                $table->boolean('count_pending_too')->default(false);
                $table->integer('workspace')->nullable();
                $table->integer('created_by');
                $table->timestamps();

                $table->unique(['user_id', 'workspace', 'created_by'], 'appt_staff_capacity_unique');
                $table->index(['workspace', 'created_by']);
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('appointment_staff_capacities');
    }
};
