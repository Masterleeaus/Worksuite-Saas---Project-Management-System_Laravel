@extends(request()->ajax() ? 'admin.layouts.blank' : 'admin.layouts.app')

@section('title', 'Create Role')

@section('content')
<form action="{{ route('admin.acl.roles.store') }}" method="POST" class="p-8 space-y-8">
    @csrf
    
    <!-- Hero Section: Role Definition -->
    <div class="p-6 bg-indigo-50/50 rounded-3xl border border-indigo-100/50 space-y-6 relative overflow-hidden group">
        <div class="absolute right-0 top-0 opacity-[0.03] -translate-y-4 translate-x-4">
            <i class="fas fa-shield-alt text-[120px] transition-transform duration-700 group-hover:rotate-12"></i>
        </div>
        
        <div class="flex items-center gap-4 text-indigo-700 relative z-10">
            <div class="w-12 h-12 rounded-2xl bg-indigo-100 flex items-center justify-center text-lg shadow-inner">
                <i class="fas fa-id-badge"></i>
            </div>
            <div>
                <h3 class="text-sm font-black uppercase tracking-[0.2em] leading-none mb-1">Role Definition</h3>
                <p class="text-[10px] text-indigo-400 font-bold uppercase tracking-widest">Assign clear identity to access levels</p>
            </div>
        </div>

        <div class="space-y-2 relative z-10">
            <label class="block text-[10px] font-black text-indigo-900/40 uppercase tracking-widest ml-1">Role Name</label>
            <div class="relative group/input">
                <span class="absolute left-4 top-1/2 -translate-y-1/2 text-indigo-300 group-focus-within/input:text-indigo-600 transition-colors">
                    <i class="fas fa-signature text-sm"></i>
                </span>
                <input type="text" name="role_name" required placeholder="e.g. Master Administrator"
                       class="w-full pl-11 pr-5 py-4 bg-white border border-indigo-100 rounded-2xl focus:ring-8 focus:ring-indigo-500/5 focus:border-indigo-500 transition-all font-bold text-gray-700 placeholder:text-gray-300 shadow-sm">
            </div>
        </div>
    </div>

    <!-- Configuration Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
        <div class="space-y-2">
            <label class="block text-xs font-black text-gray-400 uppercase tracking-[0.2em] ml-1">Display Priority</label>
            <div class="relative group">
                <span class="absolute left-4 top-1/2 -translate-y-1/2 text-gray-400 group-focus-within:text-blue-500 transition-colors">
                    <i class="fas fa-sort-amount-down text-sm"></i>
                </span>
                <input type="number" name="display_order" value="1"
                       class="w-full pl-11 pr-5 py-4 bg-gray-50/5 border border-gray-200 rounded-2xl focus:ring-4 focus:ring-blue-500/10 focus:border-blue-500 transition-all font-bold text-gray-700">
            </div>
        </div>

        <div class="space-y-2">
            <label class="block text-xs font-black text-gray-400 uppercase tracking-[0.2em] ml-1">Status Control</label>
            <div class="relative group">
                <span class="absolute left-4 top-1/2 -translate-y-1/2 text-gray-400 group-focus-within:text-emerald-500 transition-colors">
                    <i class="fas fa-toggle-on text-sm"></i>
                </span>
                <select name="active_status" 
                        class="w-full pl-11 pr-5 py-4 bg-gray-50/5 border border-gray-200 rounded-2xl focus:ring-4 focus:ring-emerald-500/10 focus:border-emerald-500 transition-all font-bold text-gray-700 appearance-none">
                    <option value="1">Active</option>
                    <option value="0">Inactive</option>
                </select>
                <i class="fas fa-chevron-down absolute right-4 top-1/2 -translate-y-1/2 text-gray-300 text-xs pointer-events-none"></i>
            </div>
        </div>
    </div>

    <!-- Action Buttons -->
    <div class="pt-8 flex flex-col sm:flex-row items-center justify-end gap-4 border-t border-gray-100">
        <button type="button" onclick="AdminManager.closeDrawer()"
                class="w-full sm:w-auto px-8 py-4 text-gray-400 font-black uppercase tracking-widest text-[10px] hover:text-gray-600 hover:bg-gray-50 rounded-2xl transition-all text-center">
            Discard
        </button>
        <button type="submit" 
                class="w-full sm:w-auto px-10 py-5 bg-gradient-to-r from-blue-600 to-indigo-700 text-white rounded-2xl font-black shadow-xl shadow-blue-500/25 hover:shadow-blue-500/40 hover:-translate-y-1 active:scale-95 transition-all flex items-center justify-center gap-3 text-sm uppercase tracking-wider">
            <i class="fas fa-check-circle text-xs"></i> Register Role
        </button>
    </div>
</form>
@endsection
