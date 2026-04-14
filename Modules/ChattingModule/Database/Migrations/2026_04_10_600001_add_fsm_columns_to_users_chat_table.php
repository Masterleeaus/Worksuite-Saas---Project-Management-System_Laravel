<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * Extends the core users_chat table with FSM/cleaning-business columns.
     * Does NOT recreate the table.
     */
    public function up(): void
    {
        Schema::table('users_chat', function (Blueprint $table) {
            if (!Schema::hasColumn('users_chat', 'message_type')) {
                $table->string('message_type')->default('text')->after('message');
                // Allowed values: 'text','image','file','voice','location'
            }

            if (!Schema::hasColumn('users_chat', 'attachment_path')) {
                $table->string('attachment_path')->nullable()->after('message_type');
            }

            if (!Schema::hasColumn('users_chat', 'is_read')) {
                $table->boolean('is_read')->default(false)->after('attachment_path');
            }

            if (!Schema::hasColumn('users_chat', 'read_at')) {
                $table->timestamp('read_at')->nullable()->after('is_read');
            }

            if (!Schema::hasColumn('users_chat', 'booking_id')) {
                // bookings.id is UUID (char 36); stored as nullable string with an index
                $table->char('booking_id', 36)->nullable()->index()->after('read_at');
            }

            if (!Schema::hasColumn('users_chat', 'channel')) {
                $table->string('channel')->default('direct')->after('booking_id');
                // Allowed values: 'direct','group','booking','broadcast'
            }

            if (!Schema::hasColumn('users_chat', 'is_deleted')) {
                $table->boolean('is_deleted')->default(false)->after('channel');
            }

            if (!Schema::hasColumn('users_chat', 'deleted_at')) {
                $table->timestamp('deleted_at')->nullable()->after('is_deleted');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users_chat', function (Blueprint $table) {
            $columns = [
                'message_type',
                'attachment_path',
                'is_read',
                'read_at',
                'booking_id',
                'channel',
                'is_deleted',
                'deleted_at',
            ];

            foreach ($columns as $column) {
                if (Schema::hasColumn('users_chat', $column)) {
                    if ($column === 'booking_id') {
                        $table->dropIndex(['booking_id']);
                    }
                    $table->dropColumn($column);
                }
            }
        });
    }
};
