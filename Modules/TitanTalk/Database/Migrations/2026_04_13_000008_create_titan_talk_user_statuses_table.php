<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('titan_talk_user_statuses')) {
            Schema::create('titan_talk_user_statuses', function (Blueprint $table) {
                $table->id();
                $table->unsignedInteger('user_id')->unique()->index();
                $table->enum('presence', ['online', 'away', 'offline'])->default('offline');
                $table->string('status_emoji', 32)->nullable();
                $table->string('status_text', 100)->nullable();
                $table->timestamp('expires_at')->nullable();
                $table->timestamp('last_seen_at')->nullable();
                $table->timestamps();

                $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('titan_talk_user_statuses');
    }
};
