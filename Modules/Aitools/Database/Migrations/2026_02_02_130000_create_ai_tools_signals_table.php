<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        if (Schema::hasTable('ai_tools_signals')) {
            return;
        }

        Schema::create('ai_tools_signals', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('company_id')->nullable()->index();
            $table->unsignedBigInteger('user_id')->nullable()->index();
            $table->string('type', 190)->index(); // e.g. invoice_overdue, job_cancelled
            $table->string('severity', 24)->default('info')->index(); // info|warning|critical
            $table->json('payload')->nullable();
            $table->timestamp('occurred_at')->nullable()->index();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        if (Schema::hasTable('ai_tools_signals')) {
            Schema::drop('ai_tools_signals');
        }
    }
};
