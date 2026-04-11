<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('titan_mega_menus')) {
            Schema::create('titan_mega_menus', function (Blueprint $table) {
                $table->id();

                $table->unsignedInteger('company_id')->nullable()->index();
                $table->foreign('company_id')
                    ->references('id')->on('companies')
                    ->onDelete('cascade')->onUpdate('cascade');

                $table->string('title');
                $table->string('slug')->nullable();
                $table->string('icon', 100)->nullable();

                // Position in the header nav bar
                $table->unsignedSmallInteger('sort_order')->default(0);

                $table->boolean('is_active')->default(true);

                // Optional: restrict to a specific module being enabled
                $table->string('required_module', 100)->nullable();

                $table->unsignedInteger('created_by')->nullable();
                $table->foreign('created_by')
                    ->references('id')->on('users')
                    ->onDelete('set null')->onUpdate('cascade');

                $table->timestamps();
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('titan_mega_menus');
    }
};
