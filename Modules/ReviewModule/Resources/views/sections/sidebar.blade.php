@if (in_array('reviewmodule', user_modules()))
    <x-menu-item icon="star" :text="__('reviewmodule::modules.reviews')" :link="Route::has('reviews.index') ? route('reviews.index') : '#'">
        <x-slot name="iconPath">
            <path d="M3.612 15.443c-.386.198-.824-.149-.746-.592l.83-4.73L.173 6.765c-.329-.314-.158-.888.283-.95l4.898-.696L7.538.792c.197-.39.73-.39.927 0l2.184 4.327 4.898.696c.441.062.612.636.282.95l-3.522 3.356.83 4.73c.078.443-.36.79-.746.592L8 13.187l-4.389 2.256z"/>
        </x-slot>

        <div class="accordionItemContent pb-2">
            @if (Route::has('reviews.index'))
                <x-sub-menu-item :link="route('reviews.index')" :text="__('reviewmodule::modules.all_reviews')" />
            @endif
            @if (Route::has('reviews.analytics') && user()->permission('view_reviews') != 'none')
                <x-sub-menu-item :link="route('reviews.analytics')" :text="__('reviewmodule::modules.analytics')" />
            @endif
        </div>
    </x-menu-item>
@endif
