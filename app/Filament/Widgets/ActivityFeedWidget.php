<?php

namespace App\Filament\Widgets;

use Filament\Widgets\Widget;

/**
 * ActivityFeedWidget
 *
 * Placeholder widget for the Titan Command Centre.
 * Renders a live activity feed for the tenant.
 *
 * Logic to be implemented in a future Titan Zero sprint.
 */
class ActivityFeedWidget extends Widget
{
    protected static ?int $sort = 4;

    protected static string $view = 'filament.widgets.activity-feed-widget';

    protected int | string | array $columnSpan = 'full';
}
