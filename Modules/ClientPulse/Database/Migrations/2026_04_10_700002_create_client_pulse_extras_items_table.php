<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('client_pulse_extras_items', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('company_id')->nullable()->index();
            $table->string('name', 150);
            $table->text('description')->nullable();
            $table->boolean('active')->default(true);
            $table->unsignedSmallInteger('sort_order')->default(0);
            $table->timestamps();
        });

        // Seed default extras items
        DB::table('client_pulse_extras_items')->insert([
            ['name' => 'Clean oven',          'description' => 'Full oven interior clean',            'active' => true, 'sort_order' => 10, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Clean fridge',        'description' => 'Full fridge interior clean',          'active' => true, 'sort_order' => 20, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Window tracks',       'description' => 'Clean window tracks and sills',       'active' => true, 'sort_order' => 30, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Inside cupboards',    'description' => 'Clean inside kitchen/bathroom cupboards', 'active' => true, 'sort_order' => 40, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Wall spot cleaning',  'description' => 'Spot clean marks from walls',         'active' => true, 'sort_order' => 50, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Blinds dusting',      'description' => 'Dust and wipe window blinds',         'active' => true, 'sort_order' => 60, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Balcony / outdoor area', 'description' => 'Sweep and clean outdoor area',     'active' => true, 'sort_order' => 70, 'created_at' => now(), 'updated_at' => now()],
        ]);
    }

    public function down(): void
    {
        Schema::dropIfExists('client_pulse_extras_items');
    }
};
