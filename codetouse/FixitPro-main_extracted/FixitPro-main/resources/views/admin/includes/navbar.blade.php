<nav class="admin-navbar">
    <a href="{{ url('/admin/dashboard') }}" class="navbar-brand">
        Fixiit<span>Pro</span>
    </a>

    <div class="navbar-links">
        <a href="{{ url('/admin/dashboard') }}" class="nav-link {{ request()->is('admin/dashboard') ? 'active' : '' }}">
            <i class="fa-solid fa-gauge-high mr-1"></i> Dashboard
        </a>
        
        <div class="nav-item-dropdown">
            <a href="javascript:void(0)" class="nav-link dropdown-trigger">
                <i class="fa-solid fa-tools mr-1"></i> Services <i class="fa-solid fa-chevron-down text-xs ml-1"></i>
            </a>
            <div class="dropdown-menu">
                <a href="#" class="dropdown-item"><i class="fa-solid fa-list"></i> All Services</a>
                <a href="#" class="dropdown-item"><i class="fa-solid fa-plus"></i> Add Service</a>
                <a href="#" class="dropdown-item"><i class="fa-solid fa-tags"></i> Categories</a>
            </div>
        </div>

        <a href="#" class="nav-link">
            <i class="fa-solid fa-calendar-check mr-1"></i> Bookings
        </a>
        <a href="#" class="nav-link">
            <i class="fa-solid fa-users mr-1"></i> Customers
        </a>

        <div class="nav-item-dropdown">
            <a href="javascript:void(0)" class="nav-link dropdown-trigger">
                <i class="fa-solid fa-chart-line mr-1"></i> Reports <i class="fa-solid fa-chevron-down text-xs ml-1"></i>
            </a>
            <div class="dropdown-menu">
                <a href="#" class="dropdown-item"><i class="fa-solid fa-file-invoice-dollar"></i> Sales Report</a>
                <a href="#" class="dropdown-item"><i class="fa-solid fa-user-gear"></i> Provider Performance</a>
            </div>
        </div>
    </div>

    <div class="nav-item-dropdown">
        <div class="user-profile dropdown-trigger">
            <div class="user-avatar">AD</div>
            <span class="font-medium text-sm">Administrator</span>
            <i class="fa-solid fa-chevron-down text-xs text-muted"></i>
        </div>
        <div class="dropdown-menu">
            <a href="#" class="dropdown-item"><i class="fa-solid fa-user"></i> My Profile</a>
            <a href="#" class="dropdown-item"><i class="fa-solid fa-gear"></i> Settings</a>
            <div class="dropdown-divider"></div>
            <form method="POST" action="{{ route('logout') }}" id="logout-form">
                @csrf
                <a href="javascript:void(0)" class="dropdown-item text-danger" onclick="document.getElementById('logout-form').submit()">
                    <i class="fa-solid fa-right-from-bracket"></i> Logout
                </a>
            </form>
        </div>
    </div>
</nav>
