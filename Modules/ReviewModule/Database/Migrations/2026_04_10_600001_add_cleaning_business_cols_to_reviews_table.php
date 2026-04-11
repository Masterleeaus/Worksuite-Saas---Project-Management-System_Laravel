<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        // Add cleaning-business specific columns to reviews table
        if (Schema::hasTable('reviews')) {
            Schema::table('reviews', function (Blueprint $table) {
                if (!Schema::hasColumn('reviews', 'moderation_status')) {
                    // pending | approved | rejected | published
                    $table->string('moderation_status', 20)->default('pending')->after('is_active');
                }
                if (!Schema::hasColumn('reviews', 'rating_punctuality')) {
                    $table->tinyInteger('rating_punctuality')->nullable()->after('review_rating');
                }
                if (!Schema::hasColumn('reviews', 'rating_quality')) {
                    $table->tinyInteger('rating_quality')->nullable()->after('rating_punctuality');
                }
                if (!Schema::hasColumn('reviews', 'rating_value')) {
                    $table->tinyInteger('rating_value')->nullable()->after('rating_quality');
                }
                if (!Schema::hasColumn('reviews', 'rating_communication')) {
                    $table->tinyInteger('rating_communication')->nullable()->after('rating_value');
                }
                if (!Schema::hasColumn('reviews', 'review_token')) {
                    // Tokenised link for public/email review submission
                    $table->string('review_token', 64)->nullable()->unique()->after('moderation_status');
                }
                if (!Schema::hasColumn('reviews', 'request_sent_at')) {
                    // When the review request was sent (2h after job completion)
                    $table->timestamp('request_sent_at')->nullable()->after('review_token');
                }
                if (!Schema::hasColumn('reviews', 'submitted_at')) {
                    $table->timestamp('submitted_at')->nullable()->after('request_sent_at');
                }
                if (!Schema::hasColumn('reviews', 'complaint_created')) {
                    // Auto-complaint flag for reviews < 3 stars
                    $table->boolean('complaint_created')->default(false)->after('submitted_at');
                }
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasTable('reviews')) {
            $cols = [
                'moderation_status', 'rating_punctuality', 'rating_quality',
                'rating_value', 'rating_communication', 'review_token',
                'request_sent_at', 'submitted_at', 'complaint_created',
            ];
            $existingCols = array_filter($cols, fn ($col) => Schema::hasColumn('reviews', $col));
            if ($existingCols) {
                Schema::table('reviews', function (Blueprint $table) use ($existingCols) {
                    $table->dropColumn(array_values($existingCols));
                });
            }
        }
    }
};
