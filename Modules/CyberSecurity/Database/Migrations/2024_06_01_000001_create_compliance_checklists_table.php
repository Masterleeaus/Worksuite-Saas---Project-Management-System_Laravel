<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{

    public function up(): void
    {
        if (!Schema::hasTable('compliance_checklists')) {
            Schema::create('compliance_checklists', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('company_id')->nullable()->index();
                $table->string('framework')->default('gdpr'); // gdpr, privacy_act_au, iso27001
                $table->string('item_key');
                $table->string('item_label');
                $table->enum('status', ['pending', 'compliant', 'non_compliant', 'not_applicable'])->default('pending');
                $table->text('notes')->nullable();
                $table->unsignedBigInteger('reviewed_by')->nullable();
                $table->timestamp('reviewed_at')->nullable();
                $table->timestamps();
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('compliance_checklists');
    }

};
