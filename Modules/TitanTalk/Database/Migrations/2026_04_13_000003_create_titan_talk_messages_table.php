<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('titan_talk_messages')) {
            Schema::create('titan_talk_messages', function (Blueprint $table) {
                $table->id();
                $table->unsignedInteger('company_id')->nullable()->index();
                $table->unsignedBigInteger('room_id')->index();
                $table->unsignedInteger('user_id')->index();
                $table->text('body');
                $table->unsignedBigInteger('thread_parent_id')->nullable()->index()->comment('If set, this is a thread reply');
                $table->unsignedInteger('thread_reply_count')->default(0);
                $table->boolean('is_edited')->default(false);
                $table->timestamps();
                $table->softDeletes();

                $table->foreign('company_id')->references('id')->on('companies')->onDelete('cascade');
                $table->foreign('room_id')->references('id')->on('titan_talk_rooms')->onDelete('cascade');
                $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
                $table->foreign('thread_parent_id')->references('id')->on('titan_talk_messages')->onDelete('set null');
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('titan_talk_messages');
    }
};
