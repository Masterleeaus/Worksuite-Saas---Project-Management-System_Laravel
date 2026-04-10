@extends('admin.layouts.app')

@section('title', 'System Settings')

@section('content')
<div class="bg-gradient-to-r from-slate-900 to-slate-800 text-white rounded-3xl shadow-2xl p-10 mb-10 relative overflow-hidden">
    <div class="relative z-10">
        <h1 class="text-4xl font-black mb-3 tracking-tight">System Settings</h1>
        <p class="text-blue-200 text-lg opacity-80">Configure core platform metrics and behavior.</p>
    </div>
    <div class="absolute right-0 top-0 bottom-0 opacity-10 flex items-center p-8 pointer-events-none">
        <i class="fas fa-cogs text-[180px] -rotate-12"></i>
    </div>
</div>

<div class="max-w-4xl mx-auto">
    <form action="{{ route('admin.settings.update') }}" method="POST" class="space-y-8">
        @csrf
        
        @foreach($settings as $group => $groupSettings)
        <div class="bg-white rounded-3xl border border-gray-100 shadow-sm overflow-hidden">
            <div class="px-8 py-6 border-b border-gray-100 bg-gray-50/50 flex items-center gap-3">
                <i class="fas fa-layer-group text-blue-500"></i>
                <h2 class="text-xl font-black text-gray-900 capitalize">{{ $group }} Settings</h2>
            </div>
            
            <div class="p-8 grid gap-6">
                @foreach($groupSettings as $setting)
                <div>
                    <label class="block text-xs font-bold text-gray-500 uppercase tracking-widest mb-2">{{ $setting->label }}</label>
                    
                    @if($setting->type === 'boolean')
                        <label class="relative inline-flex items-center cursor-pointer">
                            <input type="hidden" name="{{ $setting->key }}" value="0">
                            <input type="checkbox" name="{{ $setting->key }}" value="1" class="sr-only peer" {{ $setting->value ? 'checked' : '' }}>
                            <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-blue-300 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-blue-600"></div>
                            <span class="ml-3 text-sm font-medium text-gray-900">Enabled</span>
                        </label>
                    @elseif($setting->type === 'textarea')
                        <textarea name="{{ $setting->key }}" rows="3" class="w-full px-4 py-3 rounded-xl border-gray-200 focus:border-blue-500 focus:ring-blue-500 bg-gray-50 focus:bg-white transition-all font-medium text-gray-900">{{ $setting->value }}</textarea>
                    @else
                        <input type="{{ $setting->type === 'integer' ? 'number' : 'text' }}" 
                               name="{{ $setting->key }}" 
                               value="{{ $setting->value }}" 
                               class="w-full px-4 py-3 rounded-xl border-gray-200 focus:border-blue-500 focus:ring-blue-500 bg-gray-50 focus:bg-white transition-all font-bold text-gray-900">
                    @endif
                    
                    @if($setting->description)
                        <p class="mt-1 text-xs text-gray-400">{{ $setting->description }}</p>
                    @endif
                </div>
                @endforeach
            </div>
        </div>
        @endforeach

        <div class="flex items-center justify-end sticky bottom-6 z-50">
            <button type="submit" class="px-8 py-4 bg-blue-600 text-white rounded-2xl font-black text-lg shadow-xl shadow-blue-500/30 hover:bg-blue-700 hover:scale-105 transition-all">
                Save Changes
            </button>
        </div>
    </form>
</div>
@endsection
