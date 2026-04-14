<?php

namespace App\Filament\Pages;


/**
 * AutomationQueue
 *
 * Placeholder page – Titan Automation Queue.
 * Will display queued automations and their statuses once implemented.
 */
class AutomationQueue extends TitanPage
{
    protected static ?string $navigationIcon = 'heroicon-o-queue-list';

    protected static ?string $navigationLabel = 'Automation Queue';

    protected static ?string $title = 'Automation Queue';

    protected static ?int $navigationSort = 2;

    protected static ?string $navigationGroup = 'Titan';

    protected static string $view = 'filament.pages.automation-queue';
}
