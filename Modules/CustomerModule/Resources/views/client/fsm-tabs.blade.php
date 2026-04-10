{{--
    CustomerModule FSM overlay — three tab nav entries injected via view composer.
    Pushed to @stack('client-fsm-tabs') in resources/views/clients/show.blade.php.

    All three tabs use ajax="true" so content loads via $.easyAjax without a full-page reload.
--}}
@if (in_array('customermodule', user_modules()))

    {{-- Cleaning Info tab --}}
    <li>
        <x-tab
            :href="route('fsm.clients.cleaning-info', $client->id)"
            :text="__('customermodule::app.cleaningInfo')"
            class="cleaning-info"
            ajax="true"
        />
    </li>

    {{-- Properties tab --}}
    <li>
        <x-tab
            :href="route('fsm.clients.properties', $client->id)"
            :text="__('customermodule::app.properties')"
            class="properties"
            ajax="true"
        />
    </li>

    {{-- Booking History tab --}}
    <li>
        <x-tab
            :href="route('fsm.clients.fsm-bookings', $client->id)"
            :text="__('customermodule::app.bookingHistory')"
            class="fsm-bookings"
            ajax="true"
        />
    </li>

@endif
