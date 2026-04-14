<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        if (Schema::hasTable('titanhello_call_recordings')) {
        Schema::table('titanhello_call_recordings', function (Blueprint $table) {
            if (!Schema::hasColumn('titanhello_call_recordings', 'kind')) {
                $table->string('kind', 30)->default('call')->index();
            }
            if (!Schema::hasColumn('titanhello_call_recordings', 'fetched_at')) {
                $table->timestamp('fetched_at')->nullable();
            }
            if (!Schema::hasColumn('titanhello_call_recordings', 'fetch_status')) {
                $table->string('fetch_status', 30)->nullable()->index(); // ok/failed/pending
            }
            if (!Schema::hasColumn('titanhello_call_recordings', 'fetch_error')) {
                $table->text('fetch_error')->nullable();
            }
            if (!Schema::hasColumn('titanhello_call_recordings', 'bytes')) {
                $table->bigInteger('bytes')->nullable();
            }
            if (!Schema::hasColumn('titanhello_call_recordings', 'sha256')) {
                $table->string('sha256', 64)->nullable()->index();
            }
            if (!Schema::hasColumn('titanhello_call_recordings', 'disk')) {
                $table->string('disk', 30)->nullable(); // local/s3 etc
            }
        });
    }
    }

    public function down(): void
    {
        Schema::table('titanhello_call_recordings', function (Blueprint $table) {
            $table->dropColumn([
                'kind',
                'fetched_at',
                'fetch_status',
                'fetch_error',
                'bytes',
                'sha256',
                'disk',
            ]);
        });
    }
};
