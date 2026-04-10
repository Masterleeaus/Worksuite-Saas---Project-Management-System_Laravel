@extends('admin.layouts.app')

@section('title', 'Dashboard')

@section('content')
<div class="mb-8">
    <h1 class="text-2xl font-bold mb-1">Welcome back, Admin!</h1>
    <p class="text-muted">Here's what's happening with Fixiit Pro today.</p>
</div>

<!-- Stats Grid -->
<div class="stats-grid">
    <div class="stat-card">
        <div class="flex justify-between items-start">
            <div>
                <div class="stat-label">Total Revenue</div>
                <div class="stat-value">$12,845</div>
            </div>
            <div class="stat-icon bg-primary-soft">
                <i class="fa-solid fa-dollar-sign"></i>
            </div>
        </div>
        <div class="stat-trend trend-up">
            <i class="fa-solid fa-arrow-up"></i> 12% vs last month
        </div>
    </div>
    <div class="stat-card">
        <div class="flex justify-between items-start">
            <div>
                <div class="stat-label">Active Bookings</div>
                <div class="stat-value">48</div>
            </div>
            <div class="stat-icon bg-success-soft">
                <i class="fa-solid fa-calendar-check"></i>
            </div>
        </div>
        <div class="stat-trend trend-up">
            <i class="fa-solid fa-arrow-up"></i> 5.4% vs last week
        </div>
    </div>
    <div class="stat-card">
        <div class="flex justify-between items-start">
            <div>
                <div class="stat-label">New Customers</div>
                <div class="stat-value">124</div>
            </div>
            <div class="stat-icon bg-warning-soft">
                <i class="fa-solid fa-users"></i>
            </div>
        </div>
        <div class="stat-trend trend-down">
            <i class="fa-solid fa-arrow-down"></i> 2.1% vs last week
        </div>
    </div>
    <div class="stat-card">
        <div class="flex justify-between items-start">
            <div>
                <div class="stat-label">Service Providers</div>
                <div class="stat-value">32</div>
            </div>
            <div class="stat-icon bg-danger-soft">
                <i class="fa-solid fa-user-gear"></i>
            </div>
        </div>
        <div class="stat-trend">
            <i class="fa-solid fa-minus"></i> No change
        </div>
    </div>
</div>

<h2 class="text-xl font-bold mb-4">Quick Actions</h2>
<div class="quick-action-grid mb-8">
    <div class="quick-action-card">
        <div class="stat-icon bg-primary-soft mb-0">
            <i class="fa-solid fa-plus"></i>
        </div>
        <div>
            <div class="font-bold">Add Service</div>
            <div class="text-xs text-muted">Create a new service listing</div>
        </div>
    </div>
    <div class="quick-action-card">
        <div class="stat-icon bg-success-soft mb-0">
            <i class="fa-solid fa-user-plus"></i>
        </div>
        <div>
            <div class="font-bold">Add Provider</div>
            <div class="text-xs text-muted">Onboard new provider</div>
        </div>
    </div>
    <div class="quick-action-card">
        <div class="stat-icon bg-warning-soft mb-0">
            <i class="fa-solid fa-gear"></i>
        </div>
        <div>
            <div class="font-bold">Settings</div>
            <div class="text-xs text-muted">Configure your panel</div>
        </div>
    </div>
</div>

<!-- Recent Activities Card -->
<div class="card">
    <div class="card-header">
        <h3 class="card-title">Recent Bookings</h3>
        <button class="btn btn-primary text-xs">View All</button>
    </div>
    <div class="overflow-x-auto">
        <table class="admin-table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Customer</th>
                    <th>Service</th>
                    <th>Status</th>
                    <th>Date</th>
                    <th>Amount</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>#BK-1024</td>
                    <td>Suleman Sikandar</td>
                    <td>Home Plumbing</td>
                    <td><span class="px-2 py-1 bg-green-100 text-green-700 rounded-full text-xs font-semibold">Completed</span></td>
                    <td>Jan 5, 2026</td>
                    <td>$85.00</td>
                    <td><i class="fa-solid fa-ellipsis-vertical cursor-pointer"></i></td>
                </tr>
                <tr>
                    <td>#BK-1025</td>
                    <td>John Doe</td>
                    <td>Electrical Repair</td>
                    <td><span class="px-2 py-1 bg-yellow-100 text-yellow-700 rounded-full text-xs font-semibold">Pending</span></td>
                    <td>Jan 5, 2026</td>
                    <td>$120.00</td>
                    <td><i class="fa-solid fa-ellipsis-vertical cursor-pointer"></i></td>
                </tr>
                <tr>
                    <td>#BK-1026</td>
                    <td>Jane Smith</td>
                    <td>AC Maintenance</td>
                    <td><span class="px-2 py-1 bg-blue-100 text-blue-700 rounded-full text-xs font-semibold">Processing</span></td>
                    <td>Jan 4, 2026</td>
                    <td>$65.00</td>
                    <td><i class="fa-solid fa-ellipsis-vertical cursor-pointer"></i></td>
                </tr>
            </tbody>
        </table>
    </div>
</div>
@endsection
