@if (in_array('communication', user_modules()) && \Route::has('communications.index'))
    <x-menu-item icon="envelope" :text="__('Communications')">
        <x-slot name="iconPath">
            <path d="M0 4a2 2 0 0 1 2-2h12a2 2 0 0 1 2 2v8a2 2 0 0 1-2 2H2a2 2 0 0 1-2-2V4Zm2-1a1 1 0 0 0-1 1v.217l7 4.2 7-4.2V4a1 1 0 0 0-1-1H2Zm13 2.383-4.708 2.825L15 11.105V5.383Zm-.034 6.876-5.64-3.471L8 9.583l-1.326-.795-5.64 3.47A1 1 0 0 0 2 13h12a1 1 0 0 0 .966-.741ZM1 11.105l4.708-2.897L1 5.383v5.722Z"/>
        </x-slot>
        <div class="accordionItemContent">
            @if (\Route::has('communications.index'))
                <x-sub-menu-item :link="route('communications.index')" :text="__('Inbox')" />
            @endif
            @if (\Route::has('communications.compose'))
                <x-sub-menu-item :link="route('communications.compose')" :text="__('Compose')" />
            @endif
            @if (\Route::has('communications.templates.index'))
                <x-sub-menu-item :link="route('communications.templates.index')" :text="__('Templates')" />
            @endif
            @if (\Route::has('communications.history'))
                <x-sub-menu-item :link="route('communications.history')" :text="__('History')" />
            @endif
            @if (\Route::has('communications.bulk'))
                <x-sub-menu-item :link="route('communications.bulk')" :text="__('Bulk Send')" />
            @endif
            @if (\Route::has('communications.automations.index'))
                <x-sub-menu-item :link="route('communications.automations.index')" :text="__('Automations')" />
            @endif
        </div>
    </x-menu-item>
@endif
