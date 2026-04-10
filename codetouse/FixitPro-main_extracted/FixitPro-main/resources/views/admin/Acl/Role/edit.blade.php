@extends(request()->ajax() ? 'admin.layouts.blank' : 'admin.layouts.app')

@section('title', 'Edit Role')

@section('content')
<form action="{{ route('admin.acl.roles.update', $role->ID) }}" method="POST" class="p-8 space-y-8">
    @csrf
    
    <!-- Hero Section: Role Modification -->
    <div class="p-6 bg-indigo-50/50 rounded-3xl border border-indigo-100/50 space-y-6 relative overflow-hidden group">
        <div class="absolute right-0 top-0 opacity-[0.03] -translate-y-4 translate-x-4">
            <i class="fas fa-shield-alt text-[120px] transition-transform duration-700 group-hover:rotate-12"></i>
        </div>
        
        <div class="flex items-center gap-4 text-indigo-700 relative z-10">
            <div class="w-12 h-12 rounded-2xl bg-indigo-100 flex items-center justify-center text-lg shadow-inner">
                <i class="fas fa-user-shield"></i>
            </div>
            <div>
                <h3 class="text-sm font-black uppercase tracking-[0.2em] leading-none mb-1">Role Modification</h3>
                <p class="text-[10px] text-indigo-400 font-bold uppercase tracking-widest">Adjust level access parameters</p>
            </div>
        </div>

        <div class="space-y-2 relative z-10">
            <label class="block text-[10px] font-black text-indigo-900/40 uppercase tracking-widest ml-1">Role Title</label>
            <div class="relative group/input">
                <span class="absolute left-4 top-1/2 -translate-y-1/2 text-indigo-300 group-focus-within/input:text-indigo-600 transition-colors">
                    <i class="fas fa-signature text-sm"></i>
                </span>
                <input type="text" name="role_name" value="{{ $role->role_name }}" required
                       class="w-full pl-11 pr-5 py-4 bg-white border border-indigo-100 rounded-2xl focus:ring-8 focus:ring-indigo-500/5 focus:border-indigo-500 transition-all font-bold text-gray-700 shadow-sm">
            </div>
        </div>
    </div>

    <!-- Configuration Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
        <div class="space-y-2">
            <label class="block text-xs font-black text-gray-400 uppercase tracking-[0.2em] ml-1">Sorting Rank</label>
            <div class="relative group">
                <span class="absolute left-4 top-1/2 -translate-y-1/2 text-gray-400 group-focus-within:text-blue-500 transition-colors">
                    <i class="fas fa-sort-amount-down-alt text-sm"></i>
                </span>
                <input type="number" name="display_order" value="{{ $role->display_order }}"
                       class="w-full pl-11 pr-5 py-4 bg-gray-50/5 border border-gray-200 rounded-2xl focus:ring-4 focus:ring-blue-500/10 focus:border-blue-500 transition-all font-bold text-gray-700">
            </div>
        </div>

        <div class="space-y-2">
            <label class="block text-xs font-black text-gray-400 uppercase tracking-[0.2em] ml-1">Live Status</label>
            <div class="relative group">
                <span class="absolute left-4 top-1/2 -translate-y-1/2 text-gray-400 group-focus-within:text-emerald-500 transition-colors">
                    <i class="fas fa-power-off text-sm"></i>
                </span>
                <select name="active_status" 
                        class="w-full pl-11 pr-5 py-4 bg-gray-50/5 border border-gray-200 rounded-2xl focus:ring-4 focus:ring-emerald-500/10 focus:border-emerald-500 transition-all font-bold text-gray-700 appearance-none">
                    <option value="1" {{ $role->active_status ? 'selected' : '' }}>Active</option>
                    <option value="0" {{ !$role->active_status ? 'selected' : '' }}>Inactive</option>
                </select>
                <i class="fas fa-chevron-down absolute right-4 top-1/2 -translate-y-1/2 text-gray-300 text-xs pointer-events-none"></i>
            </div>
        </div>
    </div>

    <!-- Permissions Section -->
    <div class="space-y-6">
        <div class="flex items-center justify-between bg-white/50 p-4 rounded-2xl border border-dashed border-gray-200">
            <div>
                <label class="block text-xs font-black text-gray-400 uppercase tracking-[0.2em]">Module Permissions</label>
                <p class="text-[9px] text-gray-400 font-bold uppercase tracking-widest mt-0.5">Adjust granular module access</p>
            </div>
            <div class="flex gap-2">
                <button type="button" onclick="checkAllPermissions(true)" class="text-[9px] font-black uppercase tracking-widest text-blue-600 hover:text-blue-700 transition-colors bg-blue-50 px-4 py-2 rounded-xl border border-blue-100 shadow-sm">Select All</button>
                <button type="button" onclick="checkAllPermissions(false)" class="text-[9px] font-black uppercase tracking-widest text-gray-400 hover:text-gray-500 transition-colors bg-gray-50 px-4 py-2 rounded-xl border border-gray-100">Clear all</button>
            </div>
        </div>

        <div class="grid grid-cols-1 gap-6">
            @foreach($categories as $category)
                <div class="bg-white rounded-3xl border border-gray-100 overflow-hidden transition-all hover:shadow-2xl hover:shadow-gray-200/50 group">
                    <div class="px-6 py-4 bg-gray-50/50 border-b border-gray-100 flex items-center justify-between group-hover:bg-blue-50/30 transition-colors">
                        <div class="flex items-center gap-3">
                            <div class="w-9 h-9 rounded-xl bg-white border border-gray-100 flex items-center justify-center text-xs text-gray-400 group-hover:text-blue-500 group-hover:border-blue-200 shadow-sm transition-all">
                                <i class="fas fa-folder-open"></i>
                            </div>
                            <div>
                                <span class="text-[11px] font-black text-gray-600 uppercase tracking-widest">{{ $category->name }}</span>
                                <p class="text-[8px] text-gray-400 font-bold uppercase tracking-tighter">{{ $category->modules->count() }} Modules Available</p>
                            </div>
                        </div>
                        <input type="checkbox" onchange="toggleCategory(this, 'category_{{ $category->ID }}')"
                               class="w-5 h-5 rounded-lg border-gray-200 text-blue-600 focus:ring-blue-500/20 transition-all cursor-pointer">
                    </div>
                    
                    <div class="p-6 grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-4 category_{{ $category->ID }}">
                        @foreach($category->modules as $module)
                            <label class="relative flex items-center p-4 bg-gray-50/30 border border-gray-100 rounded-2xl cursor-pointer hover:border-blue-200 hover:bg-blue-50/20 transition-all group/item">
                                <input type="checkbox" name="module_ids[]" value="{{ $module->ID }}" 
                                       {{ in_array($module->ID, $roleModuleIds) ? 'checked' : '' }}
                                       class="w-5 h-5 rounded-lg border-gray-200 text-blue-600 focus:ring-blue-500/20 transition-all cursor-pointer">
                                <div class="ml-4">
                                    <p class="text-xs font-bold text-gray-700 group-hover/item:text-blue-700 transition-colors">{{ $module->module_name }}</p>
                                    <p class="text-[9px] text-gray-400 font-medium uppercase tracking-tight mt-0.5 opacity-60">{{ str_replace('admin/', '', $module->route) }}</p>
                                </div>
                                @if(in_array($module->ID, $roleModuleIds))
                                    <div class="absolute right-4 top-1/2 -translate-y-1/2 text-blue-500 opacity-20">
                                        <i class="fas fa-check-double text-xs"></i>
                                    </div>
                                @endif
                            </label>
                        @endforeach
                    </div>
                </div>
            @endforeach
        </div>
    </div>

    <!-- Action Buttons -->
    <div class="pt-8 flex flex-col sm:flex-row items-center justify-end gap-4 border-t border-gray-100">
        <button type="button" onclick="AdminManager.closeDrawer()"
                class="w-full sm:w-auto px-8 py-4 text-gray-400 font-black uppercase tracking-widest text-[10px] hover:text-gray-600 hover:bg-gray-50 rounded-2xl transition-all text-center">
            Cancel Changes
        </button>
        <button type="submit" 
                class="w-full sm:w-auto px-10 py-5 bg-gradient-to-r from-blue-600 to-indigo-700 text-white rounded-2xl font-black shadow-xl shadow-blue-500/25 hover:shadow-blue-500/40 hover:-translate-y-1 active:scale-95 transition-all flex items-center justify-center gap-3 text-sm uppercase tracking-wider">
            <i class="fas fa-save text-xs"></i> Update Role Settings
        </button>
    </div>
</form>

<script>
    function toggleCategory(source, categoryClass) {
        const checkboxes = document.querySelectorAll(`.${categoryClass} input[type="checkbox"]`);
        checkboxes.forEach(cb => cb.checked = source.checked);
    }

    function checkAllPermissions(status) {
        const checkboxes = document.querySelectorAll('input[name="module_ids[]"]');
        checkboxes.forEach(cb => cb.checked = status);
        // Also update category headers if any
        document.querySelectorAll('input[onchange^="toggleCategory"]').forEach(cb => cb.checked = status);
    }
</script>
@endsection
