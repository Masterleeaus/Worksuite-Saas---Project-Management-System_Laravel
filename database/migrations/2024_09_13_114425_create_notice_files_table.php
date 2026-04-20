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
        if (!Schema::hasTable('notice_board_users')) {
            Schema::create('notice_board_users', function (Blueprint $table) {
                $table->bigIncrements('id');
                $table->unsignedInteger('notice_id')->index();
                $table->foreign(['notice_id'])->references(['id'])->on('notices')->onUpdate('CASCADE')->onDelete('CASCADE');
                $table->enum('type', ['employee', 'client'])->default('employee');
                $table->unsignedInteger('user_id')->index();
                $table->foreign(['user_id'])->references(['id'])->on('users')->onUpdate('CASCADE')->onDelete('CASCADE');
            });
        }

        if (!Schema::hasTable('notice_files')) {
            Schema::create('notice_files', function (Blueprint $table) {
                $table->increments('id');
                $table->unsignedInteger('notice_id')->index();
                $table->string('filename');
                $table->text('description')->nullable();
                $table->string('google_url')->nullable();
                $table->string('hashname')->nullable();
                $table->string('size')->nullable();
                $table->string('dropbox_link')->nullable();
                $table->string('external_link')->nullable();
                $table->string('external_link_name')->nullable();
                $table->unsignedInteger('added_by')->nullable()->index();
                $table->unsignedInteger('last_updated_by')->nullable()->index();
                $table->foreign(['added_by'])->references(['id'])->on('users')->onUpdate('CASCADE')->onDelete('SET NULL');
                $table->foreign(['last_updated_by'])->references(['id'])->on('users')->onUpdate('CASCADE')->onDelete('SET NULL');
                $table->foreign(['notice_id'])->references(['id'])->on('notices')->onUpdate('CASCADE')->onDelete('CASCADE');
                $table->timestamps();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('notice_files');
        Schema::dropIfExists('notice_board_users');

    }

};
