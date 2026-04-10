<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // tbl_roles
        Schema::create('tbl_roles', function (Blueprint $table) {
            $table->id('ID');
            $table->string('role_name');
            $table->tinyInteger('active_status')->default(1)->comment('1: active, 0: inactive');
            $table->integer('display_order')->default(0);
            $table->timestamps();
        });

        // tbl_module_categories
        Schema::create('tbl_module_categories', function (Blueprint $table) {
            $table->id('ID');
            $table->string('name');
            $table->tinyInteger('active_status')->default(1);
            $table->integer('display_order')->default(0);
            $table->timestamps();
        });

        // tbl_modules
        Schema::create('tbl_modules', function (Blueprint $table) {
            $table->id('ID');
            $table->string('module_name');
            $table->string('slug')->unique();
            $table->string('route')->nullable();
            $table->unsignedBigInteger('category_id');
            $table->tinyInteger('active_status')->default(1);
            $table->integer('display_order')->default(0);
            $table->timestamps();

            $table->foreign('category_id')->references('ID')->on('tbl_module_categories')->onDelete('cascade');
        });

        // tbl_admin_user_roles
        Schema::create('tbl_admin_user_roles', function (Blueprint $table) {
            $table->id('ID');
            $table->unsignedBigInteger('admin_ID');
            $table->unsignedBigInteger('role_ID');
            $table->timestamps();

            $table->foreign('admin_ID')->references('id')->on('admins')->onDelete('cascade');
            $table->foreign('role_ID')->references('ID')->on('tbl_roles')->onDelete('cascade');
        });

        // tbl_role_privileges
        Schema::create('tbl_role_privileges', function (Blueprint $table) {
            $table->id('ID');
            $table->unsignedBigInteger('role_ID');
            $table->unsignedBigInteger('module_ID');
            $table->timestamps();

            $table->foreign('role_ID')->references('ID')->on('tbl_roles')->onDelete('cascade');
            $table->foreign('module_ID')->references('ID')->on('tbl_modules')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tbl_role_privileges');
        Schema::dropIfExists('tbl_admin_user_roles');
        Schema::dropIfExists('tbl_modules');
        Schema::dropIfExists('tbl_module_categories');
        Schema::dropIfExists('tbl_roles');
    }
};
