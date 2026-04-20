<?php

namespace Modules\FSMEquipmentWarranty\Providers;

use Illuminate\Support\ServiceProvider;

class FSMEquipmentWarrantyServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        $this->loadMigrationsFrom(__DIR__ . '/../Database/Migrations');
    }

    public function register(): void {}
}
