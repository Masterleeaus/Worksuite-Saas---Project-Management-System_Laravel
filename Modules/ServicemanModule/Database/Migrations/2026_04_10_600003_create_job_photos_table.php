<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('job_photos', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedInteger('task_id')->index();
            $table->unsignedBigInteger('uploaded_by')->nullable()->index();
            $table->enum('type', ['before', 'after', 'damage', 'other'])->default('before');
            $table->string('file_path', 512);
            $table->text('caption')->nullable();
            $table->timestamps();

            $table->foreign('task_id')->references('id')->on('tasks')->cascadeOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('job_photos');
    }
};
