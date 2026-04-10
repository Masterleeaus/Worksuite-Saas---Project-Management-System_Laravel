<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        if (Schema::hasTable('ai_tools_pulse_summaries')) {
            return;
        }

        Schema::create('ai_tools_pulse_summaries', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('company_id')->nullable()->index();
            $table->unsignedBigInteger('user_id')->nullable()->index();
            $table->date('for_date')->index();
            $table->string('window', 32)->default('daily')->index(); // daily|weekly|custom
            $table->text('summary')->nullable();
            $table->json('metrics')->nullable();
            $table->timestamps();

            $table->unique(['company_id', 'user_id', 'for_date', 'window'], 'ai_tools_pulse_unique');
        });
    }

    public function down(): void
    {
        if (Schema::hasTable('ai_tools_pulse_summaries')) {
            Schema::drop('ai_tools_pulse_summaries');
        }
    }
};
