<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('reach_conversations', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('company_id')->nullable()->index();
            $table->unsignedBigInteger('contact_id')->index();
            $table->enum('channel', ['whatsapp', 'sms', 'telegram', 'call', 'email'])->index();
            $table->string('external_id')->nullable()->index();
            $table->enum('status', ['open', 'closed', 'pending', 'spam'])->default('open')->index();
            $table->text('last_message')->nullable();
            $table->unsignedInteger('unread_count')->default(0);
            $table->unsignedBigInteger('assigned_to')->nullable()->index();
            $table->json('meta')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('reach_conversations');
    }
};
