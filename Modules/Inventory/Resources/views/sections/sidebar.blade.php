{{-- Inventory module sidebar --}}
@php($user = auth()->user())
@if (function_exists('user_modules'))
    @php($mods = (array) user_modules())
@else
    @php($mods = [])
@endif
@if (in_array('inventory', $mods) && $user && $user->can('inventory.view'))
    <li class="sidebar-item">
        <a class="sidebar-link" href="{{ route('inventory.index') }}">
            <i class="fa fa-archive sidebar-icon"></i>
            <span>{{ __('inventory::labels.menu') }}</span>
        </a>
    </li>
@endif
