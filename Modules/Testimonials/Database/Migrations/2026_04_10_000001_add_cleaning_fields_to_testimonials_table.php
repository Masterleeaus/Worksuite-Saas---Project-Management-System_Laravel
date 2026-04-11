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
        Schema::table('testimonials', function (Blueprint $table) {
            // Multi-tenant scoping
            if (!Schema::hasColumn('testimonials', 'company_id')) {
                $table->unsignedBigInteger('company_id')->nullable()->index()->after('id');
            }

            // Cleaning-business fields
            if (!Schema::hasColumn('testimonials', 'customer_name')) {
                $table->string('customer_name')->nullable()->after('company_id')
                    ->comment('Customer full name (alias for client_name)');
            }
            if (!Schema::hasColumn('testimonials', 'suburb')) {
                $table->string('suburb')->nullable()->after('customer_name')
                    ->comment('Customer suburb — local social proof');
            }
            if (!Schema::hasColumn('testimonials', 'service_type')) {
                $table->string('service_type')->nullable()->after('suburb')
                    ->comment('e.g. residential, commercial, end-of-lease');
            }
            if (!Schema::hasColumn('testimonials', 'content')) {
                $table->text('content')->nullable()->after('service_type')
                    ->comment('Full testimonial text (alias for description)');
            }
            if (!Schema::hasColumn('testimonials', 'star_rating')) {
                $table->unsignedTinyInteger('star_rating')->default(5)->after('content')
                    ->comment('1–5 star rating');
            }
            if (!Schema::hasColumn('testimonials', 'photo_path')) {
                $table->string('photo_path')->nullable()->after('star_rating')
                    ->comment('Main customer / after photo');
            }
            if (!Schema::hasColumn('testimonials', 'before_photo')) {
                $table->string('before_photo')->nullable()->after('photo_path')
                    ->comment('Before photo for before/after pair');
            }
            if (!Schema::hasColumn('testimonials', 'after_photo')) {
                $table->string('after_photo')->nullable()->after('before_photo')
                    ->comment('After photo for before/after pair');
            }
            if (!Schema::hasColumn('testimonials', 'video_url')) {
                $table->string('video_url')->nullable()->after('after_photo')
                    ->comment('YouTube or Vimeo embed URL');
            }
            if (!Schema::hasColumn('testimonials', 'is_published')) {
                $table->boolean('is_published')->default(false)->after('video_url')
                    ->comment('Admin-approved and publicly visible');
            }
            if (!Schema::hasColumn('testimonials', 'is_featured')) {
                $table->boolean('is_featured')->default(false)->after('is_published')
                    ->comment('Featured testimonial shown first');
            }
            if (!Schema::hasColumn('testimonials', 'source')) {
                $table->string('source')->default('manual')->after('is_featured')
                    ->comment('manual | review | feedback');
            }
            if (!Schema::hasColumn('testimonials', 'source_id')) {
                $table->unsignedBigInteger('source_id')->nullable()->after('source')
                    ->comment('FK to reviews.id or feedback_tickets.id');
            }
            if (!Schema::hasColumn('testimonials', 'booking_id')) {
                $table->string('booking_id')->nullable()->after('source_id')
                    ->comment('Optional link to booking record');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('testimonials', function (Blueprint $table) {
            $columns = [
                'company_id', 'customer_name', 'suburb', 'service_type', 'content',
                'star_rating', 'photo_path', 'before_photo', 'after_photo', 'video_url',
                'is_published', 'is_featured', 'source', 'source_id', 'booking_id',
            ];
            foreach ($columns as $col) {
                if (Schema::hasColumn('testimonials', $col)) {
                    $table->dropColumn($col);
                }
            }
        });
    }
};
