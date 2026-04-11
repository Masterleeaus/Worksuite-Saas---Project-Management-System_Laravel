<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('roles')) {
            return;
        }

        DB::table('roles')->insertOrIgnore([
            ['name' => 'security_guard', 'display_name' => 'Security Guard'],
        ]);
    }

    public function down(): void
    {
        if (!Schema::hasTable('roles')) {
            return;
        }

        DB::table('roles')->where('name', 'security_guard')->delete();
    }
};
