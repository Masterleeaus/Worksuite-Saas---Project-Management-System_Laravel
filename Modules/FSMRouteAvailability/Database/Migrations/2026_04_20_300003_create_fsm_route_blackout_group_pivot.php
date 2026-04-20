<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('fsm_route_blackout_group', function (Blueprint $table) {
            $table->unsignedBigInteger('fsm_route_id');
            $table->unsignedBigInteger('blackout_group_id');

            $table->primary(['fsm_route_id', 'blackout_group_id']);

            $table->foreign('fsm_route_id')
                  ->references('id')
                  ->on('fsm_routes')
                  ->onDelete('cascade');

            $table->foreign('blackout_group_id')
                  ->references('id')
                  ->on('fsm_blackout_groups')
                  ->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('fsm_route_blackout_group');
    }
};
