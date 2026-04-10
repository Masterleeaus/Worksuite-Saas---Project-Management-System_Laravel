@extends(request()->ajax() ? 'admin.layouts.blank' : 'admin.layouts.app')

@section('title', 'Create Module')

@section('content')
<form action="{{ route('admin.acl.modules.store') }}" method="POST" class="p-8 space-y-8">
    @csrf
    
    <!-- Hero Section: Module Identification -->
    <div class="p-6 bg-blue-50/50 rounded-3xl border border-blue-100/50 space-y-6 relative overflow-hidden group">
        <div class="absolute right-0 top-0 opacity-[0.03] -translate-y-4 translate-x-4">
            <i class="fas fa-cube text-[120px] transition-transform duration-700 group-hover:rotate-12"></i>
        </div>
        
        <div class="flex items-center gap-4 text-blue-700 relative z-10">
            <div class="w-12 h-12 rounded-2xl bg-blue-100 flex items-center justify-center text-lg shadow-inner">
                <i class="fas fa-fingerprint"></i>
            </div>
            <div>
                <h3 class="text-sm font-black uppercase tracking-[0.2em] leading-none mb-1">Module Identification</h3>
                <p class="text-[10px] text-blue-400 font-bold uppercase tracking-widest">Unique system identifiers</p>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 relative z-10">
            <div class="space-y-2">
                <label class="block text-[10px] font-black text-blue-900/40 uppercase tracking-widest ml-1">Module Name</label>
                <div class="relative group/input">
                    <span class="absolute left-4 top-1/2 -translate-y-1/2 text-blue-300 group-focus-within/input:text-blue-600 transition-colors">
                        <i class="fas fa-signature text-sm"></i>
                    </span>
                    <input type="text" name="module_name" required placeholder="User Management"
                           class="w-full pl-11 pr-5 py-3.5 bg-white border border-blue-100 rounded-2xl focus:ring-8 focus:ring-blue-500/5 focus:border-blue-500 transition-all font-bold text-gray-700 placeholder:text-gray-300 shadow-sm">
                </div>
            </div>
            <div class="space-y-2">
                <label class="block text-[10px] font-black text-blue-900/40 uppercase tracking-widest ml-1">System Slug</label>
                <div class="relative group/input">
                    <span class="absolute left-4 top-1/2 -translate-y-1/2 text-blue-300 group-focus-within/input:text-blue-600 transition-colors">
                        <i class="fas fa-code text-sm"></i>
                    </span>
                    <input type="text" name="slug" required placeholder="acl/users"
                           class="w-full pl-11 pr-5 py-3.5 bg-white border border-blue-100 rounded-2xl focus:ring-8 focus:ring-blue-500/5 focus:border-blue-500 transition-all font-bold text-gray-700 placeholder:text-gray-300 shadow-sm">
                </div>
            </div>
        </div>

        <div class="space-y-2 relative z-10">
            <label class="block text-[10px] font-black text-blue-900/40 uppercase tracking-widest ml-1">Route Path</label>
            <div class="relative group/input">
                <span class="absolute left-4 top-1/2 -translate-y-1/2 text-blue-300 group-focus-within/input:text-blue-600 transition-colors">
                    <i class="fas fa-link text-sm"></i>
                </span>
                <input type="text" name="route" required placeholder="admin/acl/users"
                       class="w-full pl-11 pr-5 py-3.5 bg-white border border-blue-100 rounded-2xl focus:ring-8 focus:ring-blue-500/5 focus:border-blue-500 transition-all font-bold text-gray-700 placeholder:text-gray-300 shadow-sm">
            </div>
        </div>
    </div>

    <!-- Configuration Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
        <div class="space-y-2">
            <label class="block text-xs font-black text-gray-400 uppercase tracking-[0.2em] ml-1">Parent Category</label>
            <div class="relative group">
                <span class="absolute left-4 top-1/2 -translate-y-1/2 text-gray-400 group-focus-within:text-blue-500 transition-colors pointer-events-none">
                    <i class="fas fa-layer-group text-sm"></i>
                </span>
                <select name="category_id" required
                        class="w-full pl-11 pr-5 py-3.5 bg-gray-50/5 border border-gray-200 rounded-2xl focus:ring-4 focus:ring-blue-500/10 focus:border-blue-500 transition-all font-bold text-gray-700 appearance-none">
                    @foreach($categories as $category)
                        <option value="{{ $category->ID }}">{{ $category->name }}</option>
                    @endforeach
                </select>
                <i class="fas fa-chevron-down absolute right-4 top-1/2 -translate-y-1/2 text-gray-300 text-xs pointer-events-none"></i>
            </div>
        </div>

        <div class="space-y-2">
            <label class="block text-xs font-black text-gray-400 uppercase tracking-[0.2em] ml-1">Display Priority</label>
            <div class="relative group">
                <span class="absolute left-4 top-1/2 -translate-y-1/2 text-gray-400 group-focus-within:text-blue-500 transition-colors">
                    <i class="fas fa-sort-numeric-down text-sm"></i>
                </span>
                <input type="number" name="display_order" value="1"
                       class="w-full pl-11 pr-5 py-3.5 bg-gray-50/5 border border-gray-200 rounded-2xl focus:ring-4 focus:ring-blue-500/10 focus:border-blue-500 transition-all font-bold text-gray-700">
            </div>
        </div>
    </div>

    <!-- Interface Settings -->
    <div class="flex items-center justify-between p-6 bg-gray-50/30 rounded-3xl border border-gray-100 group transition-all hover:bg-white hover:shadow-xl hover:shadow-gray-200/50">
        <div class="flex items-center gap-4">
            <div class="relative inline-flex items-center cursor-pointer">
                <input type="checkbox" name="show_in_menu" value="1" checked class="sr-only peer">
                <div class="w-14 h-7 bg-gray-200 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-0.5 after:left-[4px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-6 after:w-6 after:transition-all peer-checked:bg-blue-600 shadow-inner"></div>
            </div>
            <div>
                <label class="block text-sm font-bold text-gray-700 leading-none mb-1">Menu Visibility</label>
                <p class="text-[10px] text-gray-400 font-bold uppercase tracking-widest">Show this module in the sidebar navigation</p>
            </div>
        </div>
    </div>

    <input type="hidden" name="active_status" value="1">

    <!-- Action Buttons -->
    <div class="pt-8 flex flex-col sm:flex-row items-center justify-end gap-4 border-t border-gray-100">
        <button type="button" onclick="AdminManager.closeDrawer()"
                class="w-full sm:w-auto px-8 py-3.5 text-gray-400 font-black uppercase tracking-widest text-[10px] hover:text-gray-600 hover:bg-gray-50 rounded-2xl transition-all text-center">
            Discard
        </button>
        <button type="submit" 
                class="w-full sm:w-auto px-10 py-4 bg-gradient-to-r from-blue-600 to-indigo-700 text-white rounded-2xl font-black shadow-xl shadow-blue-500/25 hover:shadow-blue-500/40 hover:-translate-y-1 active:scale-95 transition-all flex items-center justify-center gap-3 text-sm uppercase tracking-wider">
            <i class="fas fa-plus-circle text-xs"></i> Deploy Module
        </button>
    </div>
</form>
@endsection
