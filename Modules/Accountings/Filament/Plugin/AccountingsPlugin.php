<?php

namespace Modules\Accountings\Filament\Plugin;

use Filament\Contracts\Plugin;
use Filament\Panel;
use Modules\Accountings\Filament\Pages\AdjustmentReviewQueuePage;
use Modules\Accountings\Filament\Pages\ContractProfitabilityPage;
use Modules\Accountings\Filament\Pages\PeriodClosePage;
use Modules\Accountings\Filament\Pages\SiteProfitabilityPage;
use Modules\Accountings\Filament\Pages\VisitCostExplorerPage;
use Modules\Accountings\Filament\Resources\AccountsResource;
use Modules\Accountings\Filament\Resources\InvoicesLedgerResource;
use Modules\Accountings\Filament\Resources\TransactionsResource;
use Modules\Accountings\Filament\Widgets\ContractsAtRiskWidget;
use Modules\Accountings\Filament\Widgets\MarginBySiteWidget;
use Modules\Accountings\Filament\Widgets\ProfitabilityTrendWidget;
use Modules\Accountings\Filament\Widgets\RevenueTodayWidget;
use Modules\Accountings\Filament\Widgets\UnreconciledPaymentsWidget;
use Modules\Accountings\Filament\Widgets\VisitCostVarianceWidget;

class AccountingsPlugin implements Plugin
{
    public function getId(): string
    {
        return 'accountings';
    }

    public function register(Panel $panel): void
    {
        $panel
            ->resources([
                AccountsResource::class,
                TransactionsResource::class,
                InvoicesLedgerResource::class,
            ])
            ->pages([
                VisitCostExplorerPage::class,
                SiteProfitabilityPage::class,
                ContractProfitabilityPage::class,
                PeriodClosePage::class,
                AdjustmentReviewQueuePage::class,
            ])
            ->widgets([
                RevenueTodayWidget::class,
                MarginBySiteWidget::class,
                ContractsAtRiskWidget::class,
                UnreconciledPaymentsWidget::class,
                ProfitabilityTrendWidget::class,
                VisitCostVarianceWidget::class,
            ]);
    }

    public function boot(Panel $panel): void
    {
    }

    public static function make(): static
    {
        return app(static::class);
    }
}
