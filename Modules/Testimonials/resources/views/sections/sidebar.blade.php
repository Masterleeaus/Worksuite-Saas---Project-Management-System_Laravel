@if (Route::has('admin.testimonials'))
    <x-menu-item icon="ti ti-star" :text="__('Testimonials')" :addon="false">
        <x-slot name="iconPath">
            <i class="ti ti-star"></i>
        </x-slot>
        <div class="accordionItemContent pb-2">
            <x-sub-menu-item
                :link="route('admin.testimonials')"
                :text="__('All Testimonials')"
            />
            @if (Route::has('admin.testimonials.widgets'))
                <x-sub-menu-item
                    :link="route('admin.testimonials.widgets')"
                    :text="__('Testimonial Widgets')"
                />
            @endif
        </div>
    </x-menu-item>
@endif
