<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('customerconnect_messages')) {
            return;
        }
        Schema::create('customerconnect_messages', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedInteger('company_id')->nullable()->index();
            $table->unsignedBigInteger('thread_id')->index();

            $table->string('direction')->index(); // inbound|outbound
            $table->unsignedBigInteger('sender_user_id')->nullable()->index();

            $table->text('body_text')->nullable();
            $table->longText('body_html')->nullable();

            $table->string('provider')->nullable()->index(); // twilio|vonage|telegram|mail|manual
            $table->string('provider_message_id')->nullable()->index();
            $table->string('status')->default('queued')->index(); // queued|sent|delivered|failed|read

            $table->json('meta')->nullable();

            $table->timestamp('sent_at')->nullable()->index();
            $table->timestamp('delivered_at')->nullable()->index();
            $table->timestamp('failed_at')->nullable()->index();

            $table->timestamps();

            $table->foreign('thread_id')
                ->references('id')
                ->on('customerconnect_threads')
                ->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('customerconnect_messages');
    }
};
