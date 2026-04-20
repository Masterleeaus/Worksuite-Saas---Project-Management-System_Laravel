<?php

namespace Modules\ZeroPay\Filament\Plugin;

use Filament\Contracts\Plugin;
use Filament\Panel;

class ZeroPayPlugin implements Plugin
{
    public function getId(): string
    {
        return 'zeropay';
    }

    public function register(Panel $panel): void
    {
        // Discovery is handled in TitanPanelProvider.
    }

    public function boot(Panel $panel): void
    {
    }

    public static function make(): static
    {
        return app(static::class);
    }
}
