<x-filament-panels::page>
    <x-filament::section>
        <p class="text-sm text-gray-600 dark:text-gray-400">
            ZeroPay analytics (first pass): rail mix and operational volume indicators.
        </p>
    </x-filament::section>

    <x-filament-widgets::widgets
        :widgets="$this->getHeaderWidgets()"
        :columns="$this->getHeaderWidgetsColumns()"
    />
</x-filament-panels::page>
