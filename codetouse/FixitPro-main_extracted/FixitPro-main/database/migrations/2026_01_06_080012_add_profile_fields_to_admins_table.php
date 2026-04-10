<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('admins', function (Blueprint $table) {
            $table->string('phone')->nullable()->after('email');
            $table->string('designation')->nullable()->after('phone');
            $table->string('profile_image')->nullable()->after('designation');
            $table->text('address')->nullable()->after('profile_image');
            $table->text('bio')->nullable()->after('address');
            $table->tinyInteger('active_status')->default(1)->after('bio')->comment('1: active, 0: inactive');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('admins', function (Blueprint $table) {
            $table->dropColumn(['phone', 'designation', 'profile_image', 'address', 'bio', 'active_status']);
        });
    }
};
