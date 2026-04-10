@extends('admin.layouts.app')

@section('title', 'Admin Users')

@section('content')
<div class="mb-12">
    <div class="flex flex-col md:flex-row justify-between items-center gap-6 mb-8">
        <div>
            <h2 class="text-3xl font-black text-gray-900 tracking-tight">Administrative Staff</h2>
            <p class="text-gray-500 font-bold text-xs uppercase tracking-[0.2em] mt-2 opacity-60">Manage & Monitor Authorized Personnel</p>
        </div>
        
        @if(\validatePermissions('acl/users/add'))
        <a href="javascript:void(0)" 
           onclick="AdminManager.openDrawer('{{ route('admin.acl.users.add') }}', 'Provision New Administrator')"
           class="bg-blue-600 hover:bg-blue-700 text-white px-8 py-3.5 rounded-2xl text-sm font-black shadow-lg shadow-blue-500/25 transition-all flex items-center gap-3 hover:-translate-y-1 active:scale-95">
            <i class="fas fa-user-plus"></i> Add New Admin
        </a>
        @endif
    </div>

    <div class="premium-table-card">
        <div class="overflow-x-auto">
            <table id="adminUserTable" class="w-full text-left border-collapse">
                <thead>
                    <tr>
                        <th class="w-1/3">Administrator</th>
                        <th class="w-1/4">Contact Details</th>
                        <th class="w-1/5">Access Level</th>
                        <th class="w-1/6">Status</th>
                        <th class="w-1/6 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50">
                    @foreach($admins as $admin)
                    <tr class="group">
                        <td>
                            <div class="flex items-center gap-5">
                                <div class="relative">
                                    @if($admin->profile_image)
                                        <img src="{{ asset($admin->profile_image) }}" class="w-14 h-14 rounded-2xl object-cover border-2 border-white shadow-md group-hover:scale-110 transition-all duration-500 group-hover:rotate-3">
                                    @else
                                        <div class="w-14 h-14 rounded-2xl bg-gradient-to-br from-blue-600 to-blue-800 text-white flex items-center justify-center text-lg font-black shadow-lg shadow-blue-500/20 group-hover:scale-110 transition-all duration-500 group-hover:rotate-3 border-2 border-white">
                                            {{ substr($admin->name, 0, 1) }}
                                        </div>
                                    @endif
                                    @if($admin->active_status)
                                        <div class="absolute -bottom-1 -right-1 w-4 h-4 bg-emerald-500 border-2 border-white rounded-full"></div>
                                    @endif
                                </div>
                                <div class="flex flex-col">
                                    <span class="font-black text-gray-900 group-hover:text-blue-700 transition-colors text-base tracking-tight leading-tight">{{ $admin->name }}</span>
                                    <span class="text-[9px] text-blue-500 font-bold uppercase tracking-widest mt-1">{{ $admin->designation ?? 'Administrator' }}</span>
                                </div>
                            </div>
                        </td>
                        <td>
                            <div class="flex flex-col gap-1">
                                <div class="flex items-center gap-2 text-gray-700 font-bold text-xs">
                                    <i class="fas fa-envelope text-blue-500/40 w-4"></i>
                                    {{ $admin->email }}
                                </div>
                                @if($admin->phone)
                                <div class="flex items-center gap-2 text-gray-400 font-medium text-[11px]">
                                    <i class="fas fa-phone-alt w-4 opacity-50"></i>
                                    {{ $admin->phone }}
                                </div>
                                @endif
                            </div>
                        </td>
                        <td>
                            <div class="flex items-center gap-2 text-gray-400 font-black text-[9px] uppercase tracking-wider">
                                <i class="far fa-calendar-alt"></i>
                                Joined {{ $admin->created_at->format('M d, Y') }}
                            </div>
                        </td>
                        <td>
                            @if($admin->active_status)
                                <div class="flex items-center gap-2">
                                    <span class="relative flex h-2 w-2">
                                        <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-emerald-400 opacity-75"></span>
                                        <span class="relative inline-flex rounded-full h-2 w-2 bg-emerald-500"></span>
                                    </span>
                                    <span class="text-[9px] font-black text-emerald-600 uppercase tracking-widest">Live Access</span>
                                </div>
                            @else
                                <div class="flex items-center gap-2">
                                    <span class="h-2 w-2 rounded-full bg-gray-300"></span>
                                    <span class="text-[10px] font-black text-gray-400 uppercase tracking-widest">Revoked</span>
                                </div>
                            @endif
                        </td>
                        <td class="text-right">
                            <div class="flex justify-end gap-3 translate-x-4 group-hover:translate-x-0 transition-all">
                                @if(\validatePermissions('acl/users/edit'))
                                <a href="javascript:void(0)" 
                                   onclick="AdminManager.openDrawer('{{ route('admin.acl.users.edit', $admin->id) }}', 'Modify User Credentials')"
                                   class="w-11 h-11 flex items-center justify-center text-blue-600 hover:bg-blue-600 hover:text-white rounded-xl transition-all border border-blue-100 shadow-sm hover:shadow-blue-200">
                                    <i class="fas fa-user-cog"></i>
                                </a>
                                @endif
                                
                                @if(auth('admin')->id() !== $admin->id && \validatePermissions('acl/users/delete'))
                                <a href="javascript:void(0)" 
                                   onclick="AdminUserModule.deleteUser('{{ route('admin.acl.users.delete', $admin->id) }}')"
                                   class="w-11 h-11 flex items-center justify-center text-red-600 hover:bg-red-600 hover:text-white rounded-xl transition-all border border-red-100 shadow-sm hover:shadow-red-200">
                                    <i class="fas fa-trash-alt text-sm"></i>
                                </a>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script src="{{ asset('admin_assets/js/modules/admin_user.js') }}"></script>
<script>
    $(document).ready(function() {
        $('#adminUserTable').DataTable({
            responsive: true,
            pageLength: 10,
            language: {
                search: "",
                searchPlaceholder: "Search administrators, emails, roles...",
                paginate: {
                    next: '<i class="fas fa-chevron-right text-xs"></i>',
                    previous: '<i class="fas fa-chevron-left text-xs"></i>'
                },
                emptyTable: `
                    <div class="flex flex-col items-center justify-center py-12">
                        <div class="w-20 h-20 bg-gray-50 rounded-full flex items-center justify-center text-gray-200 mb-4">
                            <i class="fas fa-user-shield text-4xl"></i>
                        </div>
                        <h3 class="text-lg font-bold text-gray-900 leading-none">No Administrators Found</h3>
                        <p class="text-gray-400 font-bold text-[10px] uppercase tracking-widest mt-2">Provision your staff members here</p>
                    </div>
                `
            }
        });
    });
</script>
@endsection
