<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('reach_campaign_embeddings', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('company_id')->nullable()->index();
            $table->unsignedBigInteger('campaign_id')->nullable()->index();
            $table->string('source_type'); // url, pdf, text, qa
            $table->string('source_url')->nullable();
            $table->longText('content');
            $table->json('embedding')->nullable();
            $table->json('meta')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('reach_campaign_embeddings');
    }
};
