<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('customerconnect_unsubscribes', function (Blueprint $table) {
    $table->bigIncrements('id');
    $table->unsignedInteger('company_id')->nullable()->index();
    $table->string('channel')->index();
    $table->string('value')->index(); // email/phone/chat_id
    $table->string('reason')->nullable();
    $table->timestamp('unsubscribed_at')->nullable();
    $table->timestamps();
});
    }

    public function down(): void
    {
        Schema::dropIfExists('customerconnect_unsubscribes');
    }
};
