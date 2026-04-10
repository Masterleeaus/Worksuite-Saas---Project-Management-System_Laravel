<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTasksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tasks', function (Blueprint $table) {
            $table->id();
            $table->integer("job_id")->nullable(false);
            $table->integer("task_type_id")->nullable(false);
            $table->string("description")->nullable();
            $table->integer("status")->default(1);
            $table->string("start_time")->nullable()->default(null);
            $table->string("end_time")->nullable()->default(null);
            $table->integer("created_by");
            $table->timestamp("deleted_at")->default(null)->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tasks');
    }
}
