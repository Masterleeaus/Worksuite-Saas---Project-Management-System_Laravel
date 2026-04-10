@extends('admin.layouts.app')

@section('title', 'Module Categories')

@section('content')
<div class="mb-12">
    <div class="flex flex-col md:flex-row justify-between items-center gap-6 mb-8">
        <div>
            <h2 class="text-3xl font-black text-gray-900 tracking-tight">System Categories</h2>
            <p class="text-gray-500 font-bold text-xs uppercase tracking-[0.2em] mt-2 opacity-60">Manage & Categorize System Modules</p>
        </div>
        
        @if(\validatePermissions('acl/categories/add'))
        <a href="javascript:void(0)" 
           onclick="AdminManager.openDrawer('{{ route('admin.acl.categories.add') }}', 'Initialize New Category')"
           class="bg-blue-600 hover:bg-blue-700 text-white px-8 py-3.5 rounded-2xl text-sm font-black shadow-lg shadow-blue-500/25 transition-all flex items-center gap-3 hover:-translate-y-1 active:scale-95">
            <i class="fas fa-layer-group"></i> Initialize Category
        </a>
        @endif
    </div>

    <div class="premium-table-card">
        <div class="overflow-x-auto">
            <table id="categoryTable" class="w-full text-left border-collapse">
                <thead>
                    <tr>
                        <th class="w-2/5">Category Definition</th>
                        <th class="w-1/5">Display Order</th>
                        <th class="w-1/5">Status</th>
                        <th class="w-1/5 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50">
                    @foreach($categories as $category)
                    <tr class="group">
                        <td>
                            <div class="flex items-center gap-5">
                                <div class="w-14 h-14 rounded-2xl bg-gray-50 flex items-center justify-center text-gray-400 group-hover:bg-blue-50 group-hover:text-blue-500 transition-all duration-300 shadow-sm border border-gray-100 group-hover:border-blue-100 group-hover:scale-110">
                                    <i class="fas fa-folder-tree text-lg"></i>
                                </div>
                                <div class="flex flex-col">
                                    <span class="font-black text-gray-900 group-hover:text-blue-700 transition-colors text-base">{{ $category->name }}</span>
                                    <span class="text-[9px] text-gray-400 font-black uppercase tracking-widest mt-1 opacity-60">System Logical Group</span>
                                </div>
                            </div>
                        </td>
                        <td>
                            <div class="flex items-center gap-2">
                                <span class="w-2 h-2 rounded-full bg-blue-400 shadow-[0_0_10px_rgba(59,130,246,0.5)]"></span>
                                <span class="text-sm font-black text-gray-700 tracking-tight">Priority #{{ $category->display_order }}</span>
                            </div>
                        </td>
                        <td>
                            @if($category->active_status)
                                <span class="inline-flex items-center px-3 py-1 rounded-lg text-[9px] font-black bg-emerald-50 text-emerald-600 border border-emerald-100 uppercase tracking-widest shadow-sm shadow-emerald-500/5">Active State</span>
                            @else
                                <span class="inline-flex items-center px-3 py-1 rounded-lg text-[9px] font-black bg-gray-50 text-gray-400 border border-gray-100 uppercase tracking-widest">Hidden State</span>
                            @endif
                        </td>
                        <td class="text-right">
                            <div class="flex justify-end gap-3 translate-x-4 group-hover:translate-x-0 transition-all">
                                @if(\validatePermissions('acl/categories/edit'))
                                <a href="javascript:void(0)" 
                                   onclick="AdminManager.openDrawer('{{ route('admin.acl.categories.edit', $category->ID) }}', 'Modify Category Definitions')"
                                   class="w-11 h-11 flex items-center justify-center text-blue-600 hover:bg-blue-600 hover:text-white rounded-xl transition-all border border-blue-100 shadow-sm hover:shadow-blue-200">
                                    <i class="fas fa-pen-nib"></i>
                                </a>
                                @endif

                                @if(\validatePermissions('acl/categories/delete'))
                                <a href="javascript:void(0)" 
                                   onclick="CategoryModule.deleteCategory('{{ route('admin.acl.categories.delete', $category->ID) }}')"
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
<script src="{{ asset('admin_assets/js/modules/category.js') }}"></script>
<script>
    $(document).ready(function() {
        $('#categoryTable').DataTable({
            responsive: true,
            pageLength: 10,
            language: {
                search: "",
                searchPlaceholder: "Search categories or priorities...",
                paginate: {
                    next: '<i class="fas fa-chevron-right text-xs"></i>',
                    previous: '<i class="fas fa-chevron-left text-xs"></i>'
                },
                emptyTable: `
                    <div class="flex flex-col items-center justify-center py-12">
                        <div class="w-20 h-20 bg-gray-50 rounded-full flex items-center justify-center text-gray-200 mb-4">
                            <i class="fas fa-layer-group text-4xl"></i>
                        </div>
                        <h3 class="text-lg font-bold text-gray-900 leading-none">No Categories Defined</h3>
                        <p class="text-gray-400 font-bold text-[10px] uppercase tracking-widest mt-2">Start by creating your first logic category</p>
                    </div>
                `
            }
        });
    });
</script>
@endsection
