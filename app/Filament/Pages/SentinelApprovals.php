<?php

namespace App\Filament\Pages;

use Filament\Pages\Page;

/**
 * SentinelApprovals
 *
 * Placeholder page – Titan Sentinel Approvals.
 * Will display items that require human approval before Titan actions proceed.
 */
class SentinelApprovals extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-shield-check';

    protected static ?string $navigationLabel = 'Sentinel Approvals';

    protected static ?string $title = 'Sentinel Approvals';

    protected static ?int $navigationSort = 4;

    protected static ?string $navigationGroup = 'Titan';

    protected static string $view = 'filament.pages.sentinel-approvals';
}
