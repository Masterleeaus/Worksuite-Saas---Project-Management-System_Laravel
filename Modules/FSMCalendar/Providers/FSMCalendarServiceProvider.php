<?php

namespace Modules\FSMCalendar\Providers;

use Illuminate\Support\ServiceProvider;

class FSMCalendarServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        $migrationsPath = __DIR__ . '/../Database/Migrations';
        if (is_dir($migrationsPath)) {
            $this->loadMigrationsFrom($migrationsPath);
        }
    }

    public function register(): void {}
}
