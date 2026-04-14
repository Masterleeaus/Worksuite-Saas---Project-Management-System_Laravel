<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Creates the fsm_order_attachments table for document/file attachments
 * per FSM job order (UploadAttachmentComponent pattern from FixitPro reference).
 *
 * Supports any file type (PDF, DOCX, images, etc.) with an optional description.
 */
return new class extends Migration {
    public function up(): void
    {
        if (Schema::hasTable('fsm_order_attachments')) {
            return;
        }

        Schema::create('fsm_order_attachments', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('fsm_order_id')->index();
            $table->unsignedBigInteger('uploaded_by')->nullable()->index(); // user.id
            $table->string('filename', 255);          // original client filename
            $table->string('path', 512);              // storage path
            $table->string('mime_type', 127)->nullable();
            $table->unsignedBigInteger('size')->default(0); // bytes
            $table->string('description', 255)->nullable();
            $table->timestamps();

            $table->foreign('fsm_order_id')
                ->references('id')->on('fsm_orders')
                ->cascadeOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('fsm_order_attachments');
    }
};
