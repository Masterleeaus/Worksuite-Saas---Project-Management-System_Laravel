<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('fsm_availability_exceptions', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('company_id')->nullable()->index();
            $table->unsignedBigInteger('person_id')->index();
            $table->dateTime('date_start');
            $table->dateTime('date_end');
            $table->enum('reason', ['leave', 'sick', 'public_holiday', 'training', 'other'])
                  ->default('other');
            $table->text('notes')->nullable();
            $table->unsignedBigInteger('approved_by')->nullable();
            $table->enum('state', ['pending', 'approved', 'rejected'])->default('pending');
            $table->timestamps();

            $table->index(['person_id', 'date_start', 'date_end']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('fsm_availability_exceptions');
    }
};
