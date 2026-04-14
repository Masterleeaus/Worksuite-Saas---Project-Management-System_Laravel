<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Modules\TitanAgents\Enums\Voice\TrainTypeEnum;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('voice_chatbot_trains')) {
            return;
        }

        Schema::create('voice_chatbot_trains', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('chatbot_id');
            $table->foreign('chatbot_id')->references('id')->on('voice_chatbots')->cascadeOnDelete();
            $table->unsignedInteger('user_id');
            $table->foreign('user_id')->references('id')->on('users')->cascadeOnDelete();
            $table->string('doc_id')->nullable();
            $table->string('name')->nullable();
            $table->enum('type', TrainTypeEnum::toArray());
            $table->string('file')->nullable();
            $table->text('url')->nullable();
            $table->text('text')->nullable();
            $table->datetime('trained_at')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('voice_chatbot_trains');
    }
};
