<?php

namespace App\Traits;

use Illuminate\Support\Str;

/**
 * Shared HasUuid trait for all modules.
 * Assigns a UUID v4 as the primary key on model creation.
 */
trait HasUuid
{
    public function initializeHasUuid(): void
    {
        $this->setKeyType('string');
        $this->incrementing = false;
    }

    public function getKeyType(): string
    {
        return 'string';
    }

    public function getIncrementing(): bool
    {
        return false;
    }

    public static function bootHasUuid(): void
    {
        static::creating(function ($model) {
            $key = $model->getKeyName();
            if (empty($model->attributes[$key])) {
                $model->attributes[$key] = (string) Str::uuid();
            }
        });
    }
}
