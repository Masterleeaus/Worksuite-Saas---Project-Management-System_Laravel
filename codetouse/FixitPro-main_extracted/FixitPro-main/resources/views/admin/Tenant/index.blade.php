@extends('admin.layouts.app')

@section('title', 'Business Management')

@section('content')
{{-- Header --}}
<div class="bg-gradient-to-r from-blue-700 to-blue-500 text-white rounded-3xl shadow-2xl p-10 mb-10 relative overflow-hidden">
    <div class="relative z-10">
        <div class="flex items-center gap-3 mb-4">
            <div class="w-3 h-3 rounded-full bg-emerald-400 animate-pulse"></div>
            <span class="text-emerald-300 text-xs font-bold uppercase tracking-widest">Platform Control</span>
        </div>
        <h1 class="text-4xl font-black mb-3 tracking-tight">Business Management</h1>
        <p class="text-blue-100 text-lg opacity-80">Manage all tenant businesses on the platform.</p>
    </div>
    <div class="absolute right-0 top-0 bottom-0 opacity-10 flex items-center p-8 pointer-events-none">
        <i class="fas fa-building text-[180px] -rotate-12"></i>
    </div>
</div>

{{-- Stats Cards --}}
<div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
    <div class="bg-white rounded-2xl border border-gray-100 p-6 shadow-sm">
        <div class="flex items-center gap-4">
            <div class="w-12 h-12 rounded-xl bg-blue-50 flex items-center justify-center">
                <i class="fas fa-building text-blue-500 text-xl"></i>
            </div>
            <div>
                <p class="text-xs font-bold text-gray-400 uppercase tracking-widest">Total</p>
                <h3 class="text-2xl font-black text-gray-900">{{ $stats['total'] }}</h3>
            </div>
        </div>
    </div>
    <div class="bg-white rounded-2xl border border-gray-100 p-6 shadow-sm">
        <div class="flex items-center gap-4">
            <div class="w-12 h-12 rounded-xl bg-emerald-50 flex items-center justify-center">
                <i class="fas fa-check-circle text-emerald-500 text-xl"></i>
            </div>
            <div>
                <p class="text-xs font-bold text-gray-400 uppercase tracking-widest">Active</p>
                <h3 class="text-2xl font-black text-emerald-600">{{ $stats['active'] }}</h3>
            </div>
        </div>
    </div>
    <div class="bg-white rounded-2xl border border-gray-100 p-6 shadow-sm">
        <div class="flex items-center gap-4">
            <div class="w-12 h-12 rounded-xl bg-amber-50 flex items-center justify-center">
                <i class="fas fa-clock text-amber-500 text-xl"></i>
            </div>
            <div>
                <p class="text-xs font-bold text-gray-400 uppercase tracking-widest">Trial</p>
                <h3 class="text-2xl font-black text-amber-600">{{ $stats['trial'] }}</h3>
            </div>
        </div>
    </div>
    <div class="bg-white rounded-2xl border border-gray-100 p-6 shadow-sm">
        <div class="flex items-center gap-4">
            <div class="w-12 h-12 rounded-xl bg-red-50 flex items-center justify-center">
                <i class="fas fa-ban text-red-500 text-xl"></i>
            </div>
            <div>
                <p class="text-xs font-bold text-gray-400 uppercase tracking-widest">Suspended</p>
                <h3 class="text-2xl font-black text-red-600">{{ $stats['suspended'] }}</h3>
            </div>
        </div>
    </div>
</div>

{{-- Table Card --}}
<div class="premium-table-card">
    <div class="px-8 py-6 border-b border-gray-100 flex items-center justify-between bg-gray-50/50">
        <h2 class="text-xl font-black text-gray-900">All Businesses</h2>
    </div>
    <div class="p-6">
        <table id="tenantsTable" class="w-full text-left stripe hover">
            <thead>
                <tr>
                    <th class="px-4 py-3">Business</th>
                    <th class="px-4 py-3">Plan</th>
                    <th class="px-4 py-3">Status</th>
                    <th class="px-4 py-3">Jobs</th>
                    <th class="px-4 py-3">Joined</th>
                    <th class="px-4 py-3 text-center">Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($tenants as $tenant)
                <tr class="group hover:bg-blue-50/30 transition-colors" data-id="{{ $tenant->ID }}">
                    <td class="px-4 py-4">
                        <div class="flex items-center gap-4">
                            <div class="w-12 h-12 rounded-2xl bg-gradient-to-br from-blue-500 to-blue-700 text-white flex items-center justify-center font-black text-lg shadow-lg shadow-blue-500/20">
                                {{ substr($tenant->business_name, 0, 1) }}
                            </div>
                            <div>
                                <span class="block font-bold text-gray-900">{{ $tenant->business_name }}</span>
                                <span class="text-xs text-gray-400">{{ $tenant->owner_name }} • {{ $tenant->email }}</span>
                            </div>
                        </div>
                    </td>
                    <td class="px-4 py-4">
                        <span class="inline-flex items-center px-3 py-1 rounded-lg text-[10px] font-black uppercase tracking-widest
                            {{ $tenant->plan_type === 'enterprise' ? 'bg-purple-50 text-purple-600 border border-purple-100' : '' }}
                            {{ $tenant->plan_type === 'professional' ? 'bg-blue-50 text-blue-600 border border-blue-100' : '' }}
                            {{ $tenant->plan_type === 'starter' ? 'bg-gray-50 text-gray-600 border border-gray-100' : '' }}
                        ">
                            {{ $tenant->plan_type }}
                        </span>
                    </td>
                    <td class="px-4 py-4">
                        <button onclick="toggleTenantStatus({{ $tenant->ID }})" 
                            class="status-badge inline-flex items-center gap-2 px-3 py-1 rounded-lg text-[10px] font-black uppercase tracking-widest cursor-pointer hover:opacity-80 transition-opacity
                            {{ $tenant->status === 'active' ? 'bg-emerald-50 text-emerald-600 border border-emerald-100' : '' }}
                            {{ $tenant->status === 'trial' ? 'bg-amber-50 text-amber-600 border border-amber-100' : '' }}
                            {{ $tenant->status === 'suspended' ? 'bg-red-50 text-red-600 border border-red-100' : '' }}
                        ">
                            <span class="w-2 h-2 rounded-full {{ $tenant->status === 'active' ? 'bg-emerald-500' : ($tenant->status === 'trial' ? 'bg-amber-500' : 'bg-red-500') }}"></span>
                            {{ $tenant->status }}
                        </button>
                    </td>
                    <td class="px-4 py-4">
                        <span class="text-gray-600 font-bold">{{ $tenant->jobs_count ?? 0 }}</span>
                    </td>
                    <td class="px-4 py-4 text-gray-500 text-sm">
                        {{ $tenant->created_at->format('M d, Y') }}
                    </td>
                    <td class="px-4 py-4">
                        <div class="flex items-center justify-center gap-2">
                            @if(validatePermissions('admin/tenants/view/{id}'))
                            <a href="{{ route('admin.tenants.view', $tenant->ID) }}" 
                               class="w-9 h-9 rounded-xl bg-gray-100 text-gray-500 hover:bg-blue-600 hover:text-white flex items-center justify-center transition-all" title="View Details">
                                <i class="fas fa-eye text-sm"></i>
                            </a>
                            @endif
                            @if(validatePermissions('admin/tenants/impersonate/{id}'))
                            <button onclick="impersonateTenant({{ $tenant->ID }}, '{{ $tenant->business_name }}')"
                               class="w-9 h-9 rounded-xl bg-amber-50 text-amber-600 hover:bg-amber-500 hover:text-white flex items-center justify-center transition-all" title="Login As">
                                <i class="fas fa-user-secret text-sm"></i>
                            </button>
                            @endif
                            @if (validatePermissions('admin/tenants/edit/{id}'))
                            <a href="{{ route('admin.tenants.edit', $tenant->ID) }}" 
                               class="w-9 h-9 rounded-xl bg-blue-50 text-blue-600 hover:bg-blue-600 hover:text-white flex items-center justify-center transition-all" title="Edit">
                                <i class="fas fa-edit text-sm"></i>
                            </a>
                            @endif
                            @if(validatePermissions('admin/tenants/delete/{id}'))
                            <button onclick="deleteTenant({{ $tenant->ID }}, '{{ $tenant->business_name }}')" 
                               class="w-9 h-9 rounded-xl bg-red-50 text-red-600 hover:bg-red-600 hover:text-white flex items-center justify-center transition-all" title="Delete">
                                <i class="fas fa-trash text-sm"></i>
                            </button>
                            @endif
                        </div>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection

@section('scripts')
<script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/responsive/2.5.0/js/dataTables.responsive.min.js"></script>
<script>
$(document).ready(function() {
    $('#tenantsTable').DataTable({
        responsive: true,
        pageLength: 25,
        order: [[4, 'desc']],
        language: {
            search: "",
            searchPlaceholder: "Search businesses...",
            emptyTable: "No businesses registered yet"
        }
    });
});

function toggleTenantStatus(id) {
    Swal.fire({
        title: 'Toggle Status',
        text: 'Are you sure you want to change this business status?',
        icon: 'question',
        showCancelButton: true,
        confirmButtonColor: '#3b82f6',
        cancelButtonColor: '#64748b'
    }).then((result) => {
        if (result.isConfirmed) {
            $.ajax({
                url: `/admin/tenants/toggle-status/${id}`,
                method: 'POST',
                headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
                success: function(response) {
                    if (response.success) {
                        Swal.fire('Success!', response.message, 'success').then(() => location.reload());
                    }
                },
                error: function() {
                    Swal.fire('Error', 'Failed to update status', 'error');
                }
            });
        }
    });
}

function impersonateTenant(id, name) {
    Swal.fire({
        title: 'Login as Business',
        html: `You will be viewing the dashboard as <strong>${name}</strong>`,
        icon: 'info',
        showCancelButton: true,
        confirmButtonText: 'Login As',
        confirmButtonColor: '#f59e0b',
        cancelButtonColor: '#64748b'
    }).then((result) => {
        if (result.isConfirmed) {
            $.ajax({
                url: `/admin/tenants/impersonate/${id}`,
                method: 'POST',
                headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
                success: function(response) {
                    if (response.success) {
                        Swal.fire('Impersonation Started', response.message, 'success');
                    }
                },
                error: function() {
                    Swal.fire('Error', 'Impersonation feature requires tenant portal setup', 'info');
                }
            });
        }
    });
}

function deleteTenant(id, name) {
    Swal.fire({
        title: 'Delete Business?',
        html: `This will permanently delete <strong>${name}</strong> and all associated data.`,
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Delete',
        confirmButtonColor: '#ef4444',
        cancelButtonColor: '#64748b'
    }).then((result) => {
        if (result.isConfirmed) {
            $.ajax({
                url: `/admin/tenants/delete/${id}`,
                method: 'GET',
                success: function(response) {
                    if (response.success) {
                        Swal.fire('Deleted!', response.message, 'success').then(() => location.reload());
                    }
                },
                error: function() {
                    Swal.fire('Error', 'Failed to delete business', 'error');
                }
            });
        }
    });
}
</script>
@endsection
