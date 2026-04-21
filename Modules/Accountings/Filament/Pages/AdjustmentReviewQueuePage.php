<?php

namespace Modules\Accountings\Filament\Pages;

use Filament\Pages\Page;

class AdjustmentReviewQueuePage extends Page
{
    protected static ?string $navigationGroup = 'Finance';
    protected static ?string $navigationLabel = 'Adjustment Review Queue';
    protected static ?string $navigationIcon = 'heroicon-o-clipboard-document-list';
    protected static string $view = 'accountings::filament.pages.adjustment-review-queue';
}
