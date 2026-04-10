@extends('admin.layouts.app')
@section('title', 'Log Details')
@section('content')
<div class="max-w-4xl mx-auto">
    <div class="mb-6"><a href="{{ route('admin.audit-logs') }}" class="text-gray-400 hover:text-blue-600 font-bold"><i class="fas fa-arrow-left"></i> Back to Logs</a></div>
    
    <div class="bg-white rounded-3xl shadow-xl overflow-hidden border border-gray-100">
        <div class="px-8 py-6 border-b border-gray-100 bg-gray-50/50 flex items-center justify-between">
            <h1 class="text-xl font-black text-gray-900">Log #{{ $log->ID }}</h1>
            <span class="px-3 py-1 rounded-lg text-xs font-black uppercase tracking-widest bg-{{ $log->action_badge_color }}-50 text-{{ $log->action_badge_color }}-600">
                {{ $log->action }}
            </span>
        </div>
        
        <div class="p-8 grid grid-cols-2 gap-8">
            <div>
                <h3 class="text-xs font-bold text-gray-400 uppercase tracking-widest mb-4">Meta Information</h3>
                <dl class="space-y-3">
                    <div class="flex justify-between">
                        <dt class="text-sm font-medium text-gray-500">Performed By</dt>
                        <dd class="text-sm font-bold text-gray-900">{{ $log->admin_name }}</dd>
                    </div>
                    <div class="flex justify-between">
                        <dt class="text-sm font-medium text-gray-500">IP Address</dt>
                        <dd class="text-sm font-bold text-gray-900">{{ $log->ip_address }}</dd>
                    </div>
                    <div class="flex justify-between">
                        <dt class="text-sm font-medium text-gray-500">Timestamp</dt>
                        <dd class="text-sm font-bold text-gray-900">{{ $log->created_at->format('M d, Y H:i:s') }}</dd>
                    </div>
                </dl>
            </div>
            
            <div>
                <h3 class="text-xs font-bold text-gray-400 uppercase tracking-widest mb-4">Target Object</h3>
                <dl class="space-y-3">
                    <div class="flex justify-between">
                        <dt class="text-sm font-medium text-gray-500">Model Type</dt>
                        <dd class="text-sm font-bold text-gray-900">{{ $log->model_type }}</dd>
                    </div>
                    <div class="flex justify-between">
                        <dt class="text-sm font-medium text-gray-500">Model ID</dt>
                        <dd class="text-sm font-bold text-gray-900">{{ $log->model_id }}</dd>
                    </div>
                    <div class="flex justify-between">
                        <dt class="text-sm font-medium text-gray-500">Description</dt>
                        <dd class="text-sm font-bold text-gray-900">{{ $log->description }}</dd>
                    </div>
                </dl>
            </div>
            
            @if($log->old_values || $log->new_values)
            <div class="col-span-2">
                <h3 class="text-xs font-bold text-gray-400 uppercase tracking-widest mb-4">Data Changes</h3>
                <div class="grid grid-cols-2 gap-4">
                    @if($log->old_values)
                    <div class="bg-red-50 rounded-xl p-4 border border-red-100">
                        <h4 class="text-xs font-bold text-red-600 uppercase mb-2">Old Values</h4>
                        <pre class="text-xs font-mono text-gray-700 overflow-x-auto">{{ json_encode($log->old_values, JSON_PRETTY_PRINT) }}</pre>
                    </div>
                    @endif
                    
                    @if($log->new_values)
                    <div class="bg-emerald-50 rounded-xl p-4 border border-emerald-100">
                        <h4 class="text-xs font-bold text-emerald-600 uppercase mb-2">New Values</h4>
                        <pre class="text-xs font-mono text-gray-700 overflow-x-auto">{{ json_encode($log->new_values, JSON_PRETTY_PRINT) }}</pre>
                    </div>
                    @endif
                </div>
            </div>
            @endif
        </div>
    </div>
</div>
@endsection
