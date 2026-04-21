<?php

namespace Modules\Accountings\Providers;

use Filament\Facades\Filament;
use Illuminate\Support\ServiceProvider;
use Modules\Accountings\Filament\Plugin\AccountingsPlugin;

class AccountingsFilamentServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        if (!class_exists(Filament::class)) {
            return;
        }

        Filament::serving(function (): void {
            $panel = Filament::getCurrentPanel();

            if ($panel === null) {
                return;
            }

            $panel->plugin(AccountingsPlugin::make());
        });
    }
}
