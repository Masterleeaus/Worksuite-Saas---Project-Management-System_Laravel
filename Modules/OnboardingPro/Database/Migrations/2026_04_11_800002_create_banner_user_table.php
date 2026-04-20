<?php
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('banner_user')) {
            return;
        }

        $resolveIdType = static function (string $table, string $column, string $fallback = 'unsignedBigInteger'): string {
            if (!Schema::hasTable($table) || !Schema::hasColumn($table, $column)) {
                return $fallback;
            }

            try {
                $type = Schema::getColumnType($table, $column);
            } catch (\Throwable $th) {
                return $fallback;
            }

            return match ($type) {
                'uuid', 'guid', 'string' => 'uuid',
                'integer', 'int', 'mediumint', 'smallint', 'tinyint' => 'unsignedInteger',
                'bigint' => 'unsignedBigInteger',
                default => $fallback,
            };
        };

        $userIdType = $resolveIdType('users', 'id', 'unsignedInteger');
        $bannerIdType = $resolveIdType('banners', 'id', 'unsignedBigInteger');

        Schema::create('banner_user', function (Blueprint $table) use ($userIdType, $bannerIdType) {
            $table->bigIncrements('id');

            if ($userIdType === 'uuid') {
                $table->uuid('user_id')->index();
            } elseif ($userIdType === 'unsignedBigInteger') {
                $table->unsignedBigInteger('user_id')->index();
            } else {
                $table->unsignedInteger('user_id')->index();
            }

            if ($bannerIdType === 'uuid') {
                $table->uuid('banner_id')->index();
            } elseif ($bannerIdType === 'unsignedInteger') {
                $table->unsignedInteger('banner_id')->index();
            } else {
                $table->unsignedBigInteger('banner_id')->index();
            }

            $table->timestamp('seen_at')->nullable();
            $table->timestamp('dismissed_at')->nullable();
            $table->timestamps();

            $table->unique(['user_id', 'banner_id']);

            if (Schema::hasTable('users')) {
                $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            }
            if (Schema::hasTable('banners')) {
                $table->foreign('banner_id')->references('id')->on('banners')->onDelete('cascade');
            }
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('banner_user');
    }
};
