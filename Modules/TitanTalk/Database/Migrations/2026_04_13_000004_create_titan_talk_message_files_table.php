<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('titan_talk_message_files')) {
            Schema::create('titan_talk_message_files', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('message_id')->index();
                $table->string('filename');
                $table->string('original_filename');
                $table->string('mime_type')->nullable();
                $table->unsignedBigInteger('file_size')->nullable();
                $table->string('disk')->default('public');
                $table->timestamps();

                $table->foreign('message_id')->references('id')->on('titan_talk_messages')->onDelete('cascade');
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('titan_talk_message_files');
    }
};
