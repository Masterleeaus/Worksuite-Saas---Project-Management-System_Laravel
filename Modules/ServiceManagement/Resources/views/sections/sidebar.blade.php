@if (in_array('servicemanagement', user_modules()))
    <x-menu-item icon="grid" :text="__('app.menu.services')">
        <x-slot name="iconPath">
            <path d="M1 2.5A1.5 1.5 0 0 1 2.5 1h3A1.5 1.5 0 0 1 7 2.5v3A1.5 1.5 0 0 1 5.5 7h-3A1.5 1.5 0 0 1 1 5.5v-3zm8 0A1.5 1.5 0 0 1 10.5 1h3A1.5 1.5 0 0 1 15 2.5v3A1.5 1.5 0 0 1 13.5 7h-3A1.5 1.5 0 0 1 9 5.5v-3zm-8 8A1.5 1.5 0 0 1 2.5 9h3A1.5 1.5 0 0 1 7 10.5v3A1.5 1.5 0 0 1 5.5 15h-3A1.5 1.5 0 0 1 1 13.5v-3zm8 0A1.5 1.5 0 0 1 10.5 9h3a1.5 1.5 0 0 1 1.5 1.5v3A1.5 1.5 0 0 1 13.5 15h-3A1.5 1.5 0 0 1 9 13.5v-3z"/>
        </x-slot>
        <div class="accordionItemContent pb-2">
            @php
                $rServices = Route::has('services.index') ? route('services.index') : null;
                $rAddons   = Route::has('services.addons.index') ? route('services.addons.index') : null;
                $rPricing  = Route::has('services.pricing.index') ? route('services.pricing.index') : null;
            @endphp

            @if ($rServices)
                <x-sub-menu-item :link="$rServices" :text="__('app.menu.allServices')"/>
            @endif

            @if ($rAddons)
                <x-sub-menu-item :link="$rAddons" :text="__('app.menu.serviceAddons')"/>
            @endif

            @if ($rPricing)
                <x-sub-menu-item :link="$rPricing" :text="__('app.menu.servicePricing')"/>
            @endif
        </div>
    </x-menu-item>
@endif
