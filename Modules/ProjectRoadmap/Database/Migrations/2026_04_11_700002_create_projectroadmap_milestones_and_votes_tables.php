<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('projectroadmap_milestones')) {
            Schema::create('projectroadmap_milestones', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('company_id')->nullable()->index();
                $table->string('title');
                $table->text('description')->nullable();
                $table->enum('status', ['planned', 'in_progress', 'completed', 'cancelled'])->default('planned');
                $table->date('target_date')->nullable();
                $table->date('completed_date')->nullable();
                $table->unsignedBigInteger('added_by')->nullable();
                $table->timestamps();
            });
        }

        if (!Schema::hasTable('projectroadmap_feature_votes')) {
            Schema::create('projectroadmap_feature_votes', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('roadmap_item_id')->index();
                $table->unsignedBigInteger('user_id')->nullable()->index();
                $table->string('voter_email')->nullable();
                $table->timestamps();

                $table->unique(['roadmap_item_id', 'user_id']);
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('projectroadmap_feature_votes');
        Schema::dropIfExists('projectroadmap_milestones');
    }
};
