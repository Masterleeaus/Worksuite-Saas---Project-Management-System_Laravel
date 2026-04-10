<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('payroll_run_line_items', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('payroll_run_id');
            $table->foreign('payroll_run_id')->references('id')->on('payroll_runs')->onDelete('cascade');
            $table->integer('user_id')->unsigned();
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->date('job_date');
            $table->dateTime('job_start')->nullable();
            $table->dateTime('job_end')->nullable();
            $table->decimal('hours_worked', 8, 4)->default(0);
            $table->string('rate_type')->default('base')->comment('base, night, saturday, sunday, public_holiday');
            $table->decimal('rate_applied', 10, 4)->default(0);
            $table->decimal('gross_pay', 10, 4)->default(0);
            $table->integer('rooms_cleaned')->default(0);
            $table->decimal('commission_amount', 10, 4)->default(0);
            $table->decimal('total_pay', 10, 4)->default(0);
            $table->boolean('is_public_holiday')->default(false);
            $table->boolean('is_overridden')->default(false);
            $table->string('source_ref')->nullable()->comment('External job card ID or reference');
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('payroll_run_line_items');
    }
};
