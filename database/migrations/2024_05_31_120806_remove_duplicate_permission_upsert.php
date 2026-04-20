<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration {

    /**
     * Run the migrations.
     */
    public function up(): void
    {
        if (!Schema::hasTable('user_permissions')) {
            return;
        }

        if (Schema::getConnection()->getDriverName() === 'mysql') {
            DB::statement('
                DELETE p1 FROM user_permissions p1
                INNER JOIN user_permissions p2
                WHERE
                    p1.id > p2.id AND
                    p1.permission_id = p2.permission_id AND
                    p1.user_id = p2.user_id;
            ');
        } else {
            DB::statement('
                DELETE FROM user_permissions
                WHERE id NOT IN (
                    SELECT MIN(id)
                    FROM user_permissions
                    GROUP BY permission_id, user_id
                )
            ');
        }

        // Step 2: Add unique constraint
        Schema::table('user_permissions', function (Blueprint $table) {
            $table->unique(['permission_id', 'user_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
