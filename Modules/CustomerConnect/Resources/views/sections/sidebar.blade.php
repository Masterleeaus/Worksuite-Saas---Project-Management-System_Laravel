@php
    // Sidebar rendering must be zero-risk:
    // - Never resolve heavy services
    // - Guard Route::has
    // - Guard permission checks
    $canView = true;
    try {
        if (function_exists('user') && user()) {
            $canView = user()->can('customerconnect.view') || user()->can('customerconnect.manage') || user()->can('customerconnect.inbox.view');
        }
    } catch (\Throwable $e) {
        $canView = true;
    }
@endphp

@if($canView && Route::has('customerconnect.index'))
<li class="nav-item">
    <a class="nav-link" href="{{ route('customerconnect.index') }}">
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
