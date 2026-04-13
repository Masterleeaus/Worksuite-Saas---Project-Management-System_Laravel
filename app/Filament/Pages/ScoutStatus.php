<?php

namespace App\Filament\Pages;

use Filament\Pages\Page;

/**
 * ScoutStatus
 *
 * Placeholder page – Titan Scout Status.
 * Will display the status of background scouting/monitoring jobs.
 */
class ScoutStatus extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-magnifying-glass';

    protected static ?string $navigationLabel = 'Scout Status';

    protected static ?string $title = 'Scout Status';

    protected static ?int $navigationSort = 3;

    protected static ?string $navigationGroup = 'Titan';

    protected static string $view = 'filament.pages.scout-status';
}
