<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('tr_package')) {
            return;
        }

        Schema::create('tr_package', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('company_id')->nullable()->index();
            $table->unsignedBigInteger('unit_id')->nullable();
            $table->unsignedBigInteger('user_id')->nullable();
            $table->unsignedBigInteger('courier_id')->nullable();
            $table->unsignedBigInteger('type_id')->nullable();
            $table->string('tracking_number')->nullable();
            $table->string('sender_name');
            $table->text('description')->nullable();
            $table->dateTime('received_at')->nullable();
            $table->dateTime('collected_at')->nullable();
            $table->string('status')->default('pending');
            $table->timestamps();

            if (Schema::hasTable('users')) {
                $table->foreign('user_id')->references('id')->on('users')->nullOnDelete();
            }
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tr_package');
    }
};
