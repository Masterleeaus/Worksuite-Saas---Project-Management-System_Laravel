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
        if (
            !Schema::hasTable('leaves')
            || !Schema::hasTable('leave_types')
            || !Schema::hasColumn('leaves', 'paid')
            || !Schema::hasColumn('leaves', 'leave_type_id')
            || !Schema::hasColumn('leave_types', 'paid')
        ) {
            return;
        }

        if (Schema::getConnection()->getDriverName() === 'mysql') {
            Leave::join('leave_types', 'leave_types.id', 'leaves.leave_type_id')
                ->update(['leaves.paid' => DB::raw('leave_types.paid')]);
            return;
        }

        $leavePaidStates = DB::table('leaves')
            ->join('leave_types', 'leave_types.id', '=', 'leaves.leave_type_id')
            ->select('leaves.id', 'leave_types.paid as leave_type_paid')
            ->get();

        foreach ($leavePaidStates as $row) {
            DB::table('leaves')
                ->where('id', $row->id)
                ->update(['paid' => $row->leave_type_paid]);
        }
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
