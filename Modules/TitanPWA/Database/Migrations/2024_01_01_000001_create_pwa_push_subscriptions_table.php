<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pwa_push_subscriptions', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id')->index();
            $table->text('endpoint');
            $table->text('p256dh');
            $table->string('auth_token', 512);
            $table->string('user_agent', 512)->nullable();
            $table->timestamps();

            // One subscription per user+endpoint pair
            $table->unique(['user_id', 'endpoint'], 'pwa_push_user_endpoint_unique');

            $table->foreign('user_id')
                ->references('id')
                ->on('users')
                ->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pwa_push_subscriptions');
    }
};
