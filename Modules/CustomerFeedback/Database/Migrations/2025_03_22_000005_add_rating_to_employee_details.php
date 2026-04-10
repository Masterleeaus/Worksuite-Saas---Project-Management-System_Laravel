<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('employee_details', function (Blueprint $table) {
            if (!Schema::hasColumn('employee_details', 'star_rating')) {
                $table->decimal('star_rating', 3, 2)->default(0.00)->after('id');
            }

            if (!Schema::hasColumn('employee_details', 'total_ratings')) {
                $table->integer('total_ratings')->default(0)->after('star_rating');
            }
        });
    }

    public function down(): void
    {
        Schema::table('employee_details', function (Blueprint $table) {
            $table->dropColumnIfExists('star_rating');
            $table->dropColumnIfExists('total_ratings');
        });
    }
};
