<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('appointment_settings')) {
            Schema::create('appointment_settings', function (Blueprint $table) {
                $table->id();
            $table->unsignedBigInteger('company_id')->nullable()->index();
                $table->unsignedBigInteger('workspace')->nullable()->index();
                $table->unsignedBigInteger('created_by')->nullable()->index();
                $table->string('key', 190)->index();
                $table->longText('value')->nullable();
                $table->timestamps();

                $table->unique(['workspace', 'key']);
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('appointment_settings');
    }
};
