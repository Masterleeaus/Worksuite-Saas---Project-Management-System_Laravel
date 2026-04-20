<?php

use App\Models\Leave;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{

    /**
     * Run the migrations.
     */
    public function up(): void
    {
        if (DB::connection()->getDriverName() === 'sqlite') {
            return;
        }

        if (
            !Schema::hasTable('leaves')
            || !Schema::hasTable('leave_types')
            || !Schema::hasColumn('leaves', 'leave_type_id')
            || !Schema::hasColumn('leaves', 'paid')
            || !Schema::hasColumn('leave_types', 'id')
            || !Schema::hasColumn('leave_types', 'paid')
        ) {
            return;
        }

        Leave::join('leave_types', 'leave_types.id', 'leaves.leave_type_id')
            ->update(['leaves.paid' => DB::raw('leave_types.paid')]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('leaves', function (Blueprint $table) {
            //
        });
    }

};
