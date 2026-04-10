<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('fsm_route_days', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name', 16);
            $table->unsignedTinyInteger('day_index');
        });

        DB::table('fsm_route_days')->insert([
            ['name' => 'Monday',    'day_index' => 0],
            ['name' => 'Tuesday',   'day_index' => 1],
            ['name' => 'Wednesday', 'day_index' => 2],
            ['name' => 'Thursday',  'day_index' => 3],
            ['name' => 'Friday',    'day_index' => 4],
            ['name' => 'Saturday',  'day_index' => 5],
            ['name' => 'Sunday',    'day_index' => 6],
        ]);
    }

    public function down(): void
    {
        Schema::dropIfExists('fsm_route_days');
    }
};
