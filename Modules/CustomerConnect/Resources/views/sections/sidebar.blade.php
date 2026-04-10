@php
    // Sidebar rendering must be zero-risk:
    // - Never resolve heavy services
    // - Guard Route::has
    // - Guard permission checks
    $canView = true;
    try {
        if (function_exists('user') && user()) {
            $canView = user()->can('customerconnect.view') || user()->can('customerconnect.manage') || user()->can('customerconnect.inbox.view') || user()->can('view_customerconnect');
        }
    } catch (\Throwable $e) {
        $canView = true;
    }

    $isClient = false;
    try {
        if (function_exists('user') && user()) {
            $isClient = user()->hasRole('client');
        }
    } catch (\Throwable $e) {
        $isClient = false;
    }
@endphp

{{-- ── Customer Self-Service Portal (for client role) ──────────────────────── --}}
@if($isClient && Route::has('customerconnect.portal.dashboard'))
<li class="nav-item">
    <a class="nav-link {{ request()->is('portal/customer*') ? 'active' : '' }}" href="{{ route('customerconnect.portal.dashboard') }}">
        <i class="fa fa-th-large"></i> <span>My Portal</span>
    </a>

    <ul class="nav flex-column ms-3">
        @if(Route::has('customerconnect.portal.bookings.index'))
            <li class="nav-item">
                <a class="nav-link {{ request()->is('portal/customer/bookings*') ? 'active' : '' }}"
                   href="{{ route('customerconnect.portal.bookings.index') }}">
                    <i class="fa fa-calendar-check me-1"></i> My Bookings
                </a>
            </li>
        @endif

        @if(Route::has('customerconnect.portal.invoices.index'))
            <li class="nav-item">
                <a class="nav-link {{ request()->is('portal/customer/invoices*') ? 'active' : '' }}"
                   href="{{ route('customerconnect.portal.invoices.index') }}">
                    <i class="fa fa-file-invoice me-1"></i> Invoices
                </a>
            </li>
        @endif

        @if(Route::has('customerconnect.portal.payments.index'))
            <li class="nav-item">
                <a class="nav-link {{ request()->is('portal/customer/payments*') ? 'active' : '' }}"
                   href="{{ route('customerconnect.portal.payments.index') }}">
                    <i class="fa fa-credit-card me-1"></i> Payments
                </a>
            </li>
        @endif

        @if(Route::has('customerconnect.portal.properties.index'))
            <li class="nav-item">
                <a class="nav-link {{ request()->is('portal/customer/properties*') ? 'active' : '' }}"
                   href="{{ route('customerconnect.portal.properties.index') }}">
                    <i class="fa fa-home me-1"></i> My Properties
                </a>
            </li>
        @endif

        @if(Route::has('customerconnect.portal.preferences.index'))
            <li class="nav-item">
                <a class="nav-link {{ request()->is('portal/customer/preferences*') ? 'active' : '' }}"
                   href="{{ route('customerconnect.portal.preferences.index') }}">
                    <i class="fa fa-cog me-1"></i> Settings
                </a>
            </li>
        @endif
    </ul>
</li>
@endif

{{-- ── Customer Connect admin/staff panel ──────────────────────────────────── --}}
@if($canView && !$isClient && Route::has('customerconnect.dashboard.index'))
<li class="nav-item">
    <a class="nav-link" href="{{ route('customerconnect.dashboard.index') }}">
        <i class="fa fa-comments"></i> <span>Customer Connect</span>
    </a>

    <ul class="nav flex-column ms-3">
        @if(Route::has('customerconnect.inbox.index'))
            <li class="nav-item"><a class="nav-link" href="{{ route('customerconnect.inbox.index') }}">Inbox</a></li>
        @endif

        @if(Route::has('customerconnect.campaigns.index'))
            <li class="nav-item"><a class="nav-link" href="{{ route('customerconnect.campaigns.index') }}">Campaigns</a></li>
        @endif
        @if(Route::has('customerconnect.audiences.index'))
            <li class="nav-item"><a class="nav-link" href="{{ route('customerconnect.audiences.index') }}">Audiences</a></li>
        @endif
        @if(Route::has('customerconnect.runs.index'))
            <li class="nav-item"><a class="nav-link" href="{{ route('customerconnect.runs.index') }}">Runs</a></li>
        @endif
        @if(Route::has('customerconnect.deliveries.index'))
            <li class="nav-item"><a class="nav-link" href="{{ route('customerconnect.deliveries.index') }}">Deliveries</a></li>
        @endif
        @if(Route::has('customerconnect.recipes.index'))
            <li class="nav-item"><a class="nav-link" href="{{ route('customerconnect.recipes.index') }}">Recipes</a></li>
        @endif
        @if(Route::has('customerconnect.settings.suppressions.index'))
            <li class="nav-item"><a class="nav-link" href="{{ route('customerconnect.settings.suppressions.index') }}">Suppression</a></li>
        @endif
        @if(Route::has('customerconnect.settings.unsubscribes.index'))
            <li class="nav-item"><a class="nav-link" href="{{ route('customerconnect.settings.unsubscribes.index') }}">Unsubscribes</a></li>
        @endif
    </ul>
</li>
@endif
