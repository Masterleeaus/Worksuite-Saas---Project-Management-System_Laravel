<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */

    public function up(): void
    {
        if (!Schema::hasColumn('mention_users', 'user_chat_id')) {
            Schema::table('mention_users', function (Blueprint $table) {
                $table->integer('user_chat_id')->unsigned()->nullable()->after('event_id');
                $table->foreign('user_chat_id')->references('id')->on('users_chat')
                    ->onDelete('cascade')
                    ->onUpdate('cascade');
            });
        }

        if (Schema::hasTable('file_storage') && Schema::hasColumn('file_storage', 'storage_location') && in_array(DB::connection()->getDriverName(), ['mysql', 'mariadb'], true)) {
            DB::statement("ALTER TABLE file_storage CHANGE COLUMN storage_location storage_location ENUM('local', 'aws_s3', 'digitalocean', 'wasabi', 'minio') NOT NULL DEFAULT 'local'");
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('mention_users', function (Blueprint $table) {
            //
        });
    }

};
