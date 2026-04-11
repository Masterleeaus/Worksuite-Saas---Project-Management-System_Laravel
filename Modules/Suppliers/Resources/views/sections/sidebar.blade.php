{{-- Sidebar injection for Suppliers --}}
@php($user = auth()->user())
@if(function_exists('user_modules') && in_array('suppliers', (array) user_modules()) && $user && $user->can('view_suppliers'))
    <li class="sidebar-item">
        <a class="sidebar-link" href="{{ route('suppliers.index') }}">
            <i class="fa fa-truck sidebar-icon"></i>
            <span>Suppliers</span>
        </a>
    </li>
@endif
