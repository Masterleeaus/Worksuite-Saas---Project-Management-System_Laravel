<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('reach_call_campaigns', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('company_id')->nullable()->index();
            $table->string('name');
            $table->enum('status', ['draft', 'scheduled', 'running', 'paused', 'completed'])->default('draft')->index();
            $table->text('call_script')->nullable();
            $table->string('twiml_url')->nullable();
            $table->enum('audience_type', ['contact_list', 'segment', 'manual']);
            $table->unsignedBigInteger('audience_id')->nullable();
            $table->string('from_number')->nullable();
            $table->timestamp('scheduled_at')->nullable();
            $table->json('stats')->nullable();
            $table->json('meta')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('reach_call_campaigns');
    }
};
