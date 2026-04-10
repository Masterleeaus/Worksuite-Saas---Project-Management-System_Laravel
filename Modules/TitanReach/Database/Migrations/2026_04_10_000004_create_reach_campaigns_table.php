<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('reach_campaigns', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('company_id')->nullable()->index();
            $table->string('name');
            $table->enum('channel', ['whatsapp', 'sms', 'telegram', 'call', 'multi']);
            $table->enum('status', ['draft', 'scheduled', 'running', 'paused', 'completed'])->default('draft')->index();
            $table->enum('audience_type', ['contact_list', 'segment', 'manual'])->default('manual');
            $table->unsignedBigInteger('audience_id')->nullable();
            $table->text('content')->nullable();
            $table->text('call_script')->nullable();
            $table->timestamp('scheduled_at')->nullable();
            $table->json('stats')->nullable();
            $table->json('meta')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('reach_campaigns');
    }
};
