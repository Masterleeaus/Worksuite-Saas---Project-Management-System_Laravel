<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        if (Schema::hasTable('customerconnect_alerts')) return;

        Schema::create('customerconnect_alerts', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('company_id')->index();
            $table->string('severity', 20)->default('info');
            $table->string('title', 191);
            $table->text('body')->nullable();
            $table->longText('context_json')->nullable();
            $table->timestamps();

            $table->index(['company_id', 'severity']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('customerconnect_alerts');
    }
};
