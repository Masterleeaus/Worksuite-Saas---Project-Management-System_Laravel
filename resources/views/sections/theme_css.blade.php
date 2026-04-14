<style>
    @php
        $activeTheme = $appTheme ?? null;
    @endphp
    :root {
        /* For Logged in user take header color a app theme*/
        /* For public pages use company specific header color example invoice,estimate public page*/
        /* For all other pages use like auth use global setting header*/
        --header_color: @if(isset($appTheme)) {{ $appTheme->header_color}} @elseif(isset($company)) {{$company->header_color}} @else {{ global_setting()->header_color}} @endif;
        --sidebar_color: {{ $activeTheme->sidebar_color ?? '#1d82f5' }};
        --sidebar_text_color: {{ $activeTheme->sidebar_text_color ?? '#ffffff' }};
        --link_color: {{ $activeTheme->link_color ?? '#ffffff' }};
        --content_border_radius: {{ isset($activeTheme->enable_rounded_theme) && (int)$activeTheme->enable_rounded_theme === 1 ? '10px' : '0px' }};
    }

    .btn-primary,
    .btn-primary.disabled:hover,
    .btn-primary:disabled:hover {
        background-color: var(--header_color) !important;
        border: 1px solid var(--header_color) !important;
    }

    .text-primary {
        color: var(--header_color) !important;
    }

    .bg-primary {
        background: var(--header_color) !important;
    }

    .datepicker table tr td, .datepicker table tr th {
        font-size: 14px;
    }

    .project-header .project-menu .p-sub-menu.active:after,
    .project-header .project-menu .p-sub-menu::after,
    .qs-current, .datepicker table tr td.active.active {
        background: var(--header_color) !important;
        text-shadow: none;
        border-color: var(--header_color) !important;
    }

    .sidebar-brand-box .sidebar-brand-dropdown a.dropdown-item:hover,
    .dropdown-item.active,
    .close-task-detail {
        background-color: var(--header_color) !important;
    }

    .pagination .page-item.active .page-link,
    .custom-control-input:checked ~ .custom-control-label::before {
        background-color: var(--header_color) !important;
        border-color: var(--header_color) !important;
    }

    .close-task-detail span {
        border: 1px solid var(--header_color) !important;
    }

    .tabs .nav .nav-link.active,
    .tabs .nav .nav-item:hover {
        border-bottom: 3px solid var(--header_color) !important;
    }

    .sidebar-light .sidebar-menu li .nav-item:focus,
    .sidebar-light .sidebar-menu li .nav-item:hover,
    .sidebar-light .sidebar-menu li .accordionItemContent a:hover {
        color: var(--header_color) !important;
    }

    .sidebar-light .accordionItem a.active {
        color: var(--header_color) !important;
    }

    .menu-item-count, .unread-notifications-count, .active-timer-count {
        background-color: var(--header_color) !important;
    }

    .dropdown-item.active .text-dark-grey {
        color: #ffffff;
    }

    .sidebar-dark,
    .sidebar-light {
        --sidebar-main-bg: var(--sidebar_color) !important;
    }

    .sidebar-dark .sidebar-menu li .nav-item,
    .sidebar-light .sidebar-menu li .nav-item,
    .sidebar-dark .sidebar-menu li .nav-item i,
    .sidebar-light .sidebar-menu li .nav-item i {
        color: var(--sidebar_text_color) !important;
    }

    .sidebar-dark .sidebar-menu li .nav-item.active,
    .sidebar-light .sidebar-menu li .nav-item.active {
        color: var(--link_color) !important;
    }

    .card,
    .modal-content,
    .btn,
    .form-control {
        border-radius: var(--content_border_radius) !important;
    }

    @if(!empty($activeTheme?->user_css))
    {!! $activeTheme->user_css !!}
    @endif

</style>
