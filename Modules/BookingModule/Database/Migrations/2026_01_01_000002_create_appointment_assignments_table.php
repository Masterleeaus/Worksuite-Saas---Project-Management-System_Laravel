<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('appointment_assignments')) {
            Schema::create('appointment_assignments', function (Blueprint $table) {
                $table->id();
            $table->unsignedBigInteger('company_id')->nullable()->index();
                $table->unsignedBigInteger('appointment_id');
                $table->unsignedBigInteger('from_user_id')->nullable();
                $table->unsignedBigInteger('to_user_id')->nullable();
                $table->string('action', 30)->default('assign'); // assign|reassign|unassign
                $table->text('note')->nullable();
                $table->unsignedBigInteger('created_by')->nullable();
                $table->integer('workspace')->nullable();
                $table->timestamps();

                $table->index(['appointment_id']);
                $table->index(['to_user_id']);
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('appointment_assignments');
    }
};
