<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateJobsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('jobs', function (Blueprint $table) {
            $table->id();
            $table->integer("job_type_id")->nullable(false);
            $table->integer("technician_id")->nullable(false);
             $table->integer("customer_id")->nullable(false);
            $table->integer("asset_id")->nullable(false);
            $table->string("job_date");
            $table->string("call_time");
            $table->string("call_ticket_no");
            $table->string("start_time")->nullable()->default(null);
            $table->string("end_time")->nullable()->default(null);
            $table->integer("status")->default(1);
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
        Schema::dropIfExists('jobs');
    }
}
