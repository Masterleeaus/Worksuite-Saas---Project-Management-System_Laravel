<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        if (!Schema::hasTable('customerconnect_assignment_cursors')) {
            Schema::create('customerconnect_assignment_cursors', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('company_id')->index();
                $table->unsignedInteger('cursor')->default(0);
                $table->timestamps();
                $table->unique('company_id');
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('customerconnect_assignment_cursors');
    }
};
