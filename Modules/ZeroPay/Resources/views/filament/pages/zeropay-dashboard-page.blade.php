<x-filament-panels::page>
    <x-filament::section>
        <p class="text-sm text-gray-600 dark:text-gray-400">
            ZeroPay operations dashboard for payment sessions, confirmations, and reconciliation queues.
        </p>
    </x-filament::section>

    <x-filament-widgets::widgets
        :widgets="$this->getHeaderWidgets()"
        :columns="$this->getHeaderWidgetsColumns()"
    />
</x-filament-panels::page>
