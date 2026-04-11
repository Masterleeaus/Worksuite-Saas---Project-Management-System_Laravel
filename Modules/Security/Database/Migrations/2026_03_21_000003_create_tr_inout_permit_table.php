<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('tr_inout_permit')) {
            return;
        }

        Schema::create('tr_inout_permit', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('company_id')->nullable()->index();
            $table->unsignedBigInteger('user_id')->nullable();
            $table->unsignedBigInteger('unit_id')->nullable();
            $table->unsignedBigInteger('task_id')->nullable();
            $table->string('visitor_name');
            $table->string('visitor_phone')->nullable();
            $table->text('purpose');
            $table->dateTime('entry_time')->nullable();
            $table->dateTime('exit_time')->nullable();
            $table->unsignedBigInteger('approved_by')->nullable();
            $table->dateTime('approved_at')->nullable();
            $table->string('approved_bm')->nullable();
            $table->dateTime('approved_bm_at')->nullable();
            $table->unsignedBigInteger('validated_by')->nullable();
            $table->dateTime('validated_at')->nullable();
            $table->string('status')->default('pending');
            $table->timestamps();

            if (Schema::hasTable('users')) {
                $table->foreign('user_id')->references('id')->on('users')->nullOnDelete();
                $table->foreign('approved_by')->references('id')->on('users')->nullOnDelete();
                $table->foreign('validated_by')->references('id')->on('users')->nullOnDelete();
            }
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tr_inout_permit');
    }
};
