@if (in_array('security', user_modules()))
    <x-menu-item icon="shield-check" :text="__('security::app.security_management')" :link="Route::has('security.dashboard') ? route('security.dashboard') : '#'">
        <x-slot name="iconPath">
            <path d="M12 1L3 5v6c0 5.55 3.84 10.74 9 12 5.16-1.26 9-6.45 9-12V5l-9-4z"/>
        </x-slot>

        <div class="accordionItemContent pb-2">
            @if (Route::has('security.dashboard'))
                <x-sub-menu-item :link="route('security.dashboard')" :text="__('security::app.dashboard')" />
            @endif
            @if (Route::has('security.access-cards.index'))
                <x-sub-menu-item :link="route('security.access-cards.index')" :text="__('security::app.access_cards')" />
            @endif
            @if (Route::has('security.inout-permits.index'))
                <x-sub-menu-item :link="route('security.inout-permits.index')" :text="__('security::app.inout_permits')" />
            @endif
            @if (Route::has('security.work-permits.index'))
                <x-sub-menu-item :link="route('security.work-permits.index')" :text="__('security::app.work_permits')" />
            @endif
            @if (Route::has('security.packages.index'))
                <x-sub-menu-item :link="route('security.packages.index')" :text="__('security::app.packages')" />
            @endif
            @if (Route::has('security.parking.index'))
                <x-sub-menu-item :link="route('security.parking.index')" :text="__('security::app.parking')" />
            @endif
            @if (Route::has('security.notes.index'))
                <x-sub-menu-item :link="route('security.notes.index')" :text="__('security::app.notes')" />
            @endif
        </div>
    </x-menu-item>
@endif
