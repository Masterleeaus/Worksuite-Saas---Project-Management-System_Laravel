<x-filament-panels::page>
    <div class="space-y-4">
        <x-filament::section>
            <p class="text-sm text-gray-600 dark:text-gray-400">
                Titan Command Centre — widgets are registered and ready. Full implementation pending Titan Zero sprint.
            </p>
        </x-filament::section>

        @livewire(\App\Filament\Widgets\SystemSignalsWidget::class)
        @livewire(\App\Filament\Widgets\JobsTodayWidget::class)
        @livewire(\App\Filament\Widgets\RevenueWidget::class)
        @livewire(\App\Filament\Widgets\ActivityFeedWidget::class)
        @livewire(\App\Filament\Widgets\TitanChatWidget::class)
    </div>
</x-filament-panels::page>
