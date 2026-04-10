<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    /**
     * Create the service_addons table.
     * Stores add-ons that can be attached to cleaning services (e.g. Inside Fridge, Oven, Windows).
     */
    public function up(): void
    {
        Schema::create('service_addons', function (Blueprint $table) {
            $table->id();
            $table->string('name', 191);
            $table->decimal('price', 10, 2)->default(0);
            $table->unsignedInteger('duration_extra')->nullable()->comment('Extra minutes added to job duration');
            $table->string('service_id', 36)->nullable()->index();
            $table->boolean('is_active')->default(true);
            $table->softDeletes();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('service_addons');
    }
};
