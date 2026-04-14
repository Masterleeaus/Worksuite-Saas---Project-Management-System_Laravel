<x-filament-panels::page>
    <x-filament::section>
        <p class="text-sm text-gray-600 dark:text-gray-400">
            Titan Command Centre — widgets are registered and ready. Full implementation pending Titan Zero sprint.
        </p>
    </x-filament::section>

    <x-filament-widgets::widgets
        :widgets="$this->getHeaderWidgets()"
        :columns="$this->getHeaderWidgetsColumns()"
    />
</x-filament-panels::page>
