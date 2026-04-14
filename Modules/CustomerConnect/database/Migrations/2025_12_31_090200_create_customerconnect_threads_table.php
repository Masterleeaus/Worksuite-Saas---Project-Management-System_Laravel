<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('customerconnect_threads', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedInteger('company_id')->nullable()->index();
            $table->unsignedBigInteger('contact_id')->index();

            $table->string('channel')->index(); // email|sms|whatsapp|telegram
            $table->string('external_thread_id')->nullable()->index();
            $table->string('subject')->nullable();

            $table->string('status')->default('open')->index(); // open|pending|closed
            $table->unsignedBigInteger('assigned_to')->nullable()->index();

            $table->timestamp('last_message_at')->nullable()->index();
            $table->string('last_message_preview', 191)->nullable();

            $table->timestamps();

            $table->foreign('contact_id')
                ->references('id')
                ->on('customerconnect_contacts')
                ->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('customerconnect_threads');
    }
};
