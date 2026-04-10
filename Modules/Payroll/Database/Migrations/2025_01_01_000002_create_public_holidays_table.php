<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('public_holidays', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('company_id')->unsigned()->nullable();
            $table->foreign('company_id')->references('id')->on('companies')->onDelete('cascade');
            $table->date('holiday_date');
            $table->string('name');
            $table->string('state')->nullable()->comment('AU state/territory code: NSW, VIC, QLD, SA, WA, TAS, ACT, NT, or null for national');
            $table->boolean('is_national')->default(false);
            $table->boolean('is_manual')->default(false)->comment('Manually added by admin');
            $table->timestamps();
            $table->index(['company_id', 'holiday_date', 'state']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('public_holidays');
    }
};
