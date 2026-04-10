<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('schedule_assignments')) {
            Schema::create('schedule_assignments', function (Blueprint $table) {
                $table->id();
            $table->unsignedBigInteger('company_id')->nullable()->index();
                $table->unsignedBigInteger('schedule_id')->index();
                $table->unsignedBigInteger('from_user_id')->nullable()->index();
                $table->unsignedBigInteger('to_user_id')->nullable()->index();
                $table->string('action', 40)->index(); // assign | reassign | unassign
                $table->longText('note')->nullable();
                $table->unsignedBigInteger('created_by')->nullable()->index();
                $table->unsignedBigInteger('workspace')->nullable()->index();
                $table->timestamps();
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('schedule_assignments');
    }
};
