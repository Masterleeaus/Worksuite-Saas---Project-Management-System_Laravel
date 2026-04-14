<?php

namespace App\Filament\Widgets;

use App\Models\User;
use App\Scopes\ActiveScope;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Illuminate\Database\Eloquent\Builder;

class TotalClientsWidget extends BaseWidget
{
    protected static ?int $sort = 6;

    protected function getStats(): array
    {
        $companyId = auth()->user()?->company_id;

        $query = User::query()
            ->withoutGlobalScope(ActiveScope::class)
            ->whereNull('users.is_client_contact')
            ->whereHas('roles', fn (Builder $builder) => $builder->where('name', 'client'));

        if ($companyId) {
            $query->where('users.company_id', $companyId);
        }

        return [
            Stat::make('Total Clients', (string) $query->count())
                ->description('Client accounts in this tenant')
                ->color('primary'),
        ];
    }
}
