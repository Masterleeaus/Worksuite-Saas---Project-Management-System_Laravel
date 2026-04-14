<?php

namespace App\Filament\Pages;

use App\Filament\Widgets\ActivityFeedWidget;
use App\Filament\Widgets\JobsTodayWidget;
use App\Filament\Widgets\RevenueWidget;
use App\Filament\Widgets\SystemSignalsWidget;
use App\Filament\Widgets\TitanChatWidget;

/**
 * CommandCentre
 *
 * Titan Command Centre – the primary landing page for the Filament panel.
 * Acts as the unified control surface for the Worksuite Titan layer.
 *
 * Widget logic is pending Titan Zero implementation; skeletons are registered.
 */
class CommandCentre extends TitanPage
{
    protected static ?string $navigationIcon = 'heroicon-o-command-line';

    protected static ?string $navigationLabel = 'Command Centre';

    protected static ?string $title = 'Titan Command Centre';

    protected static ?int $navigationSort = 1;

    protected static ?string $navigationGroup = 'Titan';

    protected static string $view = 'filament.pages.command-centre';

    /**
     * Widgets rendered in the header area of this page.
     *
     * @return array<class-string>
     */
    public function getHeaderWidgets(): array
    {
        return [
            SystemSignalsWidget::class,
            JobsTodayWidget::class,
            RevenueWidget::class,
            ActivityFeedWidget::class,
            TitanChatWidget::class,
        ];
    }
}
