<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        if (Schema::hasTable('document_templates')) {
            return;
        }
        Schema::create('document_templates', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('template_type'); // 'client','employee','contract','letter'
            $table->string('document_type'); // 'employment_letter','service_contract','proposal',etc.
            $table->longText('html_content'); // template body with {{merge_fields}}
            $table->json('required_fields')->nullable();
            $table->boolean('is_active')->default(true);
            $table->boolean('is_global')->default(false);
            $table->unsignedBigInteger('company_id')->nullable();
            $table->unsignedBigInteger('created_by');
            $table->boolean('is_approved')->default(false);
            $table->unsignedBigInteger('approved_by')->nullable();
            $table->timestamp('approved_at')->nullable();
            $table->softDeletes();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('document_templates');
    }
};
