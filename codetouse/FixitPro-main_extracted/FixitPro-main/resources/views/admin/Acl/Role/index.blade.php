@extends('admin.layouts.app')

@section('title', 'Role Management')

@section('content')
<div class="mb-12">
    <div class="flex flex-col md:flex-row justify-between items-center gap-6 mb-8">
        <div>
            <h2 class="text-3xl font-black text-gray-900 tracking-tight">Access Control Roles</h2>
            <p class="text-gray-500 font-bold text-xs uppercase tracking-[0.2em] mt-2 opacity-60">Define & Distribute System Permissions</p>
        </div>
        
        @if(\validatePermissions('acl/roles/add'))
        <a href="javascript:void(0)" 
           onclick="AdminManager.openDrawer('{{ route('admin.acl.roles.add') }}', 'Create New System Role')"
           class="bg-blue-600 hover:bg-blue-700 text-white px-8 py-3.5 rounded-2xl text-sm font-black shadow-lg shadow-blue-500/25 transition-all flex items-center gap-3 hover:-translate-y-1 active:scale-95">
            <i class="fas fa-user-shield"></i> Create New Role
        </a>
        @endif
    </div>

    <div class="premium-table-card">
        <div class="overflow-x-auto">
            <table id="roleTable" class="w-full text-left border-collapse">
                <thead>
                    <tr>
                        <th class="w-2/5">Role Definition</th>
                        <th class="w-1/5">Weight/Priority</th>
                        <th class="w-1/5">Status</th>
                        <th class="w-1/5 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50">
                    @foreach($roles as $role)
                    <tr class="group">
                        <td>
                            <div class="flex items-center gap-5">
                                <div class="w-14 h-14 rounded-2xl bg-gradient-to-br from-gray-50 to-gray-100 flex items-center justify-center text-gray-400 group-hover:from-blue-600 group-hover:to-blue-800 group-hover:text-white transition-all duration-500 shadow-sm border border-gray-100 group-hover:border-blue-500 group-hover:scale-110 group-hover:rotate-3">
                                    <span class="text-lg font-black">{{ substr($role->role_name, 0, 1) }}</span>
                                </div>
                                <div class="flex flex-col">
                                    <span class="font-black text-gray-900 group-hover:text-blue-700 transition-colors text-base uppercase tracking-tight">{{ $role->role_name }}</span>
                                    <span class="text-[9px] text-gray-400 font-black uppercase tracking-widest mt-1 opacity-60">Security Level Authorization</span>
                                </div>
                            </div>
                        </td>
                        <td>
                            <div class="flex items-center gap-2.5">
                                <div class="px-3 py-1 bg-gray-50 rounded-lg border border-gray-100 group-hover:border-blue-100 group-hover:bg-blue-50 transition-all">
                                    <span class="text-sm font-black text-gray-700 group-hover:text-blue-600 tracking-tight">Lvl #{{ $role->display_order }}</span>
                                </div>
                            </div>
                        </td>
                        <td>
                            @if($role->active_status)
                                <div class="flex items-center gap-2">
                                    <span class="relative flex h-2 w-2">
                                        <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-emerald-400 opacity-75"></span>
                                        <span class="relative inline-flex rounded-full h-2 w-2 bg-emerald-500"></span>
                                    </span>
                                    <span class="text-[9px] font-black text-emerald-600 uppercase tracking-widest">Live Authorization</span>
                                </div>
                            @else
                                <div class="flex items-center gap-2">
                                    <span class="h-2 w-2 rounded-full bg-gray-300"></span>
                                    <span class="text-[10px] font-black text-gray-400 uppercase tracking-widest">Hold/Disabled</span>
                                </div>
                            @endif
                        </td>
                        <td class="text-right">
                            <div class="flex justify-end gap-3 translate-x-4 group-hover:translate-x-0 transition-all">
                                @if(\validatePermissions('acl/roles/edit'))
                                <a href="javascript:void(0)" 
                                   onclick="AdminManager.openDrawer('{{ route('admin.acl.roles.edit', $role->ID) }}', 'Modify Role Settings')"
                                   class="w-11 h-11 flex items-center justify-center text-blue-600 hover:bg-blue-600 hover:text-white rounded-xl transition-all border border-blue-100 shadow-sm hover:shadow-blue-200">
                                    <i class="fas fa-sliders-h"></i>
                                </a>
                                @endif

                                @if(\validatePermissions('acl/roles/delete'))
                                <a href="javascript:void(0)" 
                                   onclick="RoleModule.deleteRole('{{ route('admin.acl.roles.delete', $role->ID) }}')"
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
<script src="{{ asset('admin_assets/js/modules/role.js') }}"></script>
<script>
    $(document).ready(function() {
        $('#roleTable').DataTable({
            responsive: true,
            pageLength: 10,
            language: {
                search: "",
                searchPlaceholder: "Search roles, security levels...",
                paginate: {
                    next: '<i class="fas fa-chevron-right text-xs"></i>',
                    previous: '<i class="fas fa-chevron-left text-xs"></i>'
                },
                emptyTable: `
                    <div class="flex flex-col items-center justify-center py-12">
                        <div class="w-20 h-20 bg-gray-50 rounded-full flex items-center justify-center text-gray-200 mb-4">
                            <i class="fas fa-user-shield text-4xl"></i>
                        </div>
                        <h3 class="text-lg font-bold text-gray-900 leading-none">No Roles Registered</h3>
                        <p class="text-gray-400 font-bold text-[10px] uppercase tracking-widest mt-2">Initialize your security levels here</p>
                    </div>
                `
            }
        });
    });
</script>
@endsection
