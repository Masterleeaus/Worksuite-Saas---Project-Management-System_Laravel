<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateComplianceDocumentTypesTable extends Migration
{
    public function up()
    {
        if (Schema::hasTable('compliance_document_types')) {
            return;
        }

        Schema::create('compliance_document_types', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->string('code')->unique();
            $table->json('vertical')->nullable();
            $table->boolean('is_mandatory')->default(false);
            $table->unsignedInteger('renewal_period_months')->nullable();
            $table->text('description')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('compliance_document_types');
    }
}
