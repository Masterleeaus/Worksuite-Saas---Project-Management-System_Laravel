<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        if (Schema::hasTable('customerconnect_delivery_events')) return;

        Schema::create('customerconnect_delivery_events', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('company_id')->nullable()->index();
            $table->unsignedBigInteger('delivery_id');
            $table->string('event_type', 50);
            $table->longText('payload_json')->nullable();
            $table->timestamps();

            $table->index(['delivery_id', 'event_type']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('customerconnect_delivery_events');
    }
};
