<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('fsm_locations', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('company_id')->nullable()->index();
            $table->string('name', 256);
            $table->unsignedBigInteger('partner_id')->nullable()->index(); // FK → clients/users
            $table->unsignedBigInteger('territory_id')->nullable()->index();
            $table->string('street', 256)->nullable();
            $table->string('city', 128)->nullable();
            $table->string('state', 128)->nullable();
            $table->string('zip', 32)->nullable();
            $table->string('country', 128)->nullable();
            $table->decimal('latitude', 10, 7)->nullable();
            $table->decimal('longitude', 10, 7)->nullable();
            $table->text('notes')->nullable(); // access codes – encrypted by TitanZero
            $table->boolean('active')->default(true);
            $table->timestamps();

            $table->foreign('territory_id')->references('id')->on('fsm_territories')->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('fsm_locations');
    }
};
