<?php
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('ext_creative_suite_documents')) {
            return;
        }
        Schema::create('ext_creative_suite_documents', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedInteger('company_id')->nullable()->index();
            $table->unsignedInteger('user_id')->index();
            $table->uuid('uuid')->unique()->index();
            $table->string('name')->default('Untitled Design');
            $table->string('preview')->nullable();
            $table->json('payload')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('ext_creative_suite_documents');
    }
};
