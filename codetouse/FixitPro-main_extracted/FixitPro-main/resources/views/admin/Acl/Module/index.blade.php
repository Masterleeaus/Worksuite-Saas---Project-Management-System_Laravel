@extends('admin.layouts.app')

@section('title', 'System Modules')

@section('content')
<div class="mb-12">
    <div class="flex flex-col md:flex-row justify-between items-center gap-6 mb-8">
        <div>
            <h2 class="text-3xl font-black text-gray-900 tracking-tight">Modules Repository</h2>
            <p class="text-gray-500 font-bold text-xs uppercase tracking-[0.2em] mt-2 opacity-60">System Security & Access Definitions</p>
        </div>
        
        @if(\validatePermissions('acl/modules/add'))
        <a href="javascript:void(0)" 
           onclick="AdminManager.openDrawer('{{ route('admin.acl.modules.add') }}', 'Register New System Module')"
           class="bg-blue-600 hover:bg-blue-700 text-white px-8 py-3.5 rounded-2xl text-sm font-black shadow-lg shadow-blue-500/25 transition-all flex items-center gap-3 hover:-translate-y-1 active:scale-95">
            <i class="fas fa-plus-circle"></i> Register Module
        </a>
        @endif
    </div>

    <div class="premium-table-card">
        <div class="overflow-x-auto">
            <table id="moduleTable" class="w-full text-left border-collapse">
                <thead>
                    <tr>
                        <th class="w-2/5">Module Identity</th>
                        <th class="w-2/5">System Route</th>
                        <th class="w-1/5 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50">
                    @foreach($modules as $module)
                    <tr class="group">
                        <td>
                            <div class="flex items-center gap-5">
                                <div class="w-14 h-14 rounded-2xl bg-gray-50 flex items-center justify-center text-gray-400 group-hover:bg-blue-50 group-hover:text-blue-500 transition-all duration-300 shadow-sm border border-gray-100 group-hover:border-blue-100 group-hover:scale-110">
                                    <i class="fas fa-shield-halved text-lg"></i>
                                </div>
                                <div class="flex flex-col">
                                    <span class="font-black text-gray-900 group-hover:text-blue-700 transition-colors text-base">{{ $module->module_name }}</span>
                                    <div class="flex items-center gap-2.5 mt-1.5">
                                        <span class="text-[9px] px-2 py-0.5 bg-blue-50 text-blue-600 font-black uppercase tracking-widest rounded-md border border-blue-100/50">{{ $module->slug }}</span>
                                        <span class="w-1 h-1 bg-gray-300 rounded-full"></span>
                                        <span class="text-[9px] text-gray-400 font-black uppercase tracking-widest">{{ $module->category->name ?? 'Global' }}</span>
                                    </div>
                                </div>
                            </div>
                        </td>
                        <td>
                            <code class="px-4 py-2 bg-gray-50 text-[10px] font-black text-gray-500 rounded-xl border border-gray-100 tracking-tight group-hover:bg-white group-hover:border-blue-200 group-hover:text-blue-600 transition-all">
                                {{ $module->route }}
                            </code>
                        </td>
                        <td class="text-right">
                            <div class="flex justify-end gap-3 translate-x-4 group-hover:translate-x-0 transition-all">
                                @if(\validatePermissions('acl/modules/edit'))
                                <a href="javascript:void(0)" 
                                   onclick="AdminManager.openDrawer('{{ route('admin.acl.modules.edit', $module->ID) }}', 'Adjust Module Definitions')"
                                   class="w-11 h-11 flex items-center justify-center text-blue-600 hover:bg-blue-600 hover:text-white rounded-xl transition-all border border-blue-100 shadow-sm hover:shadow-blue-200">
                                    <i class="fas fa-sliders-h"></i>
                                </a>
                                @endif

                                @if(\validatePermissions('acl/modules/delete'))
                                <a href="javascript:void(0)" 
                                   onclick="ModuleObj.deleteModule('{{ route('admin.acl.modules.delete', $module->ID) }}')"
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
</div>
@endsection

@section('scripts')
<script src="{{ asset('admin_assets/js/modules/module.js') }}"></script>
<script>
    $(document).ready(function() {
        $('#moduleTable').DataTable({
            responsive: true,
            pageLength: 10,
            language: {
                search: "",
                searchPlaceholder: "Search modules, routes or categories...",
                paginate: {
                    next: '<i class="fas fa-chevron-right text-xs"></i>',
                    previous: '<i class="fas fa-chevron-left text-xs"></i>'
                },
                emptyTable: `
                    <div class="flex flex-col items-center justify-center py-12">
                        <div class="w-20 h-20 bg-gray-50 rounded-full flex items-center justify-center text-gray-200 mb-4">
                            <i class="fas fa-cube text-4xl"></i>
                        </div>
                        <h3 class="text-lg font-bold text-gray-900 leading-none">No Modules Registered</h3>
                        <p class="text-gray-400 font-bold text-[10px] uppercase tracking-widest mt-2">Get started by creating your first system module</p>
                    </div>
                `
            },
            drawCallback: function() {
                $('.dataTables_paginate > .paginate_button').addClass('transition-all duration-300');
            }
        });
    });
</script>
@endsection
