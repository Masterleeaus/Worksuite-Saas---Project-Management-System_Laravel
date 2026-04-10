<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('evidence_vault_photos')) {
            Schema::create('evidence_vault_photos', function (Blueprint $table) {
                $table->id();

                $table->unsignedBigInteger('submission_id');
                $table->foreign('submission_id')
                    ->references('id')->on('evidence_vault_submissions')
                    ->onDelete('cascade')->onUpdate('cascade');

                // Original filename as uploaded by the client.
                $table->string('original_filename');

                // Hashed/stored filename on disk.
                $table->string('disk_filename');

                // Storage disk (matches evidence_vault.storage_disk config).
                $table->string('disk')->default('local');

                // Relative path within the disk (excluding filename).
                $table->string('disk_path');

                // MIME type detected server-side.
                $table->string('mime_type')->nullable();

                // File size in bytes after optional server-side compression.
                $table->unsignedBigInteger('file_size')->nullable();

                // Whether this photo is the "locked-site" alternative to a signature.
                $table->boolean('is_site_locked_photo')->default(false);

                $table->timestamps();
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('evidence_vault_photos');
    }
};
