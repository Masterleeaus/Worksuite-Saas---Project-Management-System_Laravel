<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        if (Schema::hasTable('titanhello_calls')) {
        Schema::table('titanhello_calls', function (Blueprint $table) {
            if (!Schema::hasColumn('titanhello_calls', 'voicemail_flag')) {
                $table->boolean('voicemail_flag')->default(false)->index();
            }
            if (!Schema::hasColumn('titanhello_calls', 'voicemail_received_at')) {
                $table->timestamp('voicemail_received_at')->nullable()->index();
            }
            if (!Schema::hasColumn('titanhello_calls', 'voicemail_recording_id')) {
                $table->unsignedBigInteger('voicemail_recording_id')->nullable()->index();
            }
            if (!Schema::hasColumn('titanhello_calls', 'voicemail_transcript_artifact_id')) {
                $table->unsignedBigInteger('voicemail_transcript_artifact_id')->nullable();
            }
            if (!Schema::hasColumn('titanhello_calls', 'voicemail_summary_artifact_id')) {
                $table->unsignedBigInteger('voicemail_summary_artifact_id')->nullable();
            }
        });
    }
    }

    public function down(): void
    {
        Schema::table('titanhello_calls', function (Blueprint $table) {
            $table->dropColumn([
                'voicemail_flag',
                'voicemail_received_at',
                'voicemail_recording_id',
                'voicemail_transcript_artifact_id',
                'voicemail_summary_artifact_id',
            ]);
        });
    }
};
