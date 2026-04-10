<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('fsm_order_photos', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('fsm_order_id')->index();
            $table->unsignedBigInteger('uploaded_by')->nullable()->index(); // worker user id
            $table->string('type', 32)->default('photo'); // 'photo' | 'signature'
            $table->string('path', 512);                  // storage path
            $table->text('caption')->nullable();
            $table->timestamps();

            $table->foreign('fsm_order_id')->references('id')->on('fsm_orders')->cascadeOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('fsm_order_photos');
    }
};
