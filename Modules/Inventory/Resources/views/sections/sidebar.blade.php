{{-- Inventory module sidebar --}}
@php($user = auth()->user())
@if ($user && $user->can('inventory.view'))
    <li class="sidebar-item">
        <a class="sidebar-link has-arrow" href="#" aria-expanded="false">
            <i class="fa fa-archive sidebar-icon"></i>
            <span>{{ __('inventory::labels.menu') }}</span>
        </a>
        <ul class="collapse first-level" aria-expanded="false">
            <li class="sidebar-item">
                <a href="{{ route('inventory.index') }}" class="sidebar-link">
                    <i class="fa fa-tachometer sidebar-icon"></i>
                    <span>{{ __('inventory::labels.dashboard') }}</span>
                </a>
            </li>
            <li class="sidebar-item">
                <a href="{{ route('inventory.warehouses.index') }}" class="sidebar-link">
                    <i class="fa fa-building sidebar-icon"></i>
                    <span>{{ __('inventory::labels.warehouses') }}</span>
                </a>
            </li>
            <li class="sidebar-item">
                <a href="{{ route('inventory.items.index') }}" class="sidebar-link">
                    <i class="fa fa-cubes sidebar-icon"></i>
                    <span>{{ __('inventory::labels.stock') }}</span>
                </a>
            </li>
            <li class="sidebar-item">
                <a href="{{ route('inventory.movements.index') }}" class="sidebar-link">
                    <i class="fa fa-exchange sidebar-icon"></i>
                    <span>{{ __('inventory::labels.movements') }}</span>
                </a>
            </li>
            <li class="sidebar-item">
                <a href="{{ route('inventory.transfers.index') }}" class="sidebar-link">
                    <i class="fa fa-truck sidebar-icon"></i>
                    <span>{{ __('inventory::labels.transfers') }}</span>
                </a>
            </li>
            <li class="sidebar-item">
                <a href="{{ route('inventory.purchasing.index') }}" class="sidebar-link">
                    <i class="fa fa-shopping-cart sidebar-icon"></i>
                    <span>{{ __('inventory::labels.purchase_orders') }}</span>
                </a>
            </li>
            @if ($user->can('inventory.suppliers.view'))
            <li class="sidebar-item">
                <a href="{{ route('inventory.suppliers.index') }}" class="sidebar-link">
                    <i class="fa fa-handshake-o sidebar-icon"></i>
                    <span>{{ __('inventory::labels.suppliers') }}</span>
                </a>
            </li>
            @endif
        </ul>
    </li>
@endif
