@extends('admin.layouts.app')
@section('title', 'Audit Logs')
@section('content')
<div class="premium-table-card">
    <div class="px-8 py-6 border-b border-gray-100 flex items-center justify-between bg-gray-50/50">
        <h2 class="text-xl font-black text-gray-900">System Audit Logs</h2>
    </div>
    <div class="p-6">
        <div class="flex gap-4 mb-6">
            <select id="actionFilter" class="px-4 py-2 rounded-lg border-gray-200 text-sm font-bold">
                <option value="">All Actions</option>
                @foreach($actions as $action)
                    <option value="{{ $action }}" {{ request('action') == $action ? 'selected' : '' }}>{{ ucfirst($action) }}</option>
                @endforeach
            </select>
            <select id="adminFilter" class="px-4 py-2 rounded-lg border-gray-200 text-sm font-bold">
                <option value="">All Admins</option>
                @foreach($admins as $admin)
                    <option value="{{ $admin->id }}" {{ request('admin_id') == $admin->id ? 'selected' : '' }}>{{ $admin->name }}</option>
                @endforeach
            </select>
        </div>
        
        <div class="space-y-4">
            @foreach($logs as $log)
            <div class="flex items-start gap-4 p-4 rounded-xl border border-gray-100 hover:bg-gray-50 transition-colors">
                <div class="w-10 h-10 rounded-full bg-{{ $log->action_badge_color }}-50 flex items-center justify-center flex-shrink-0">
                    <i class="fas {{ $log->action_icon }} text-{{ $log->action_badge_color }}-500"></i>
                </div>
                <div class="flex-1">
                    <div class="flex items-center justify-between">
                        <p class="text-sm font-bold text-gray-900">
                            <span class="text-blue-600">{{ $log->admin_name }}</span> 
                            {{ ucfirst($log->action) }} 
                            <span class="text-gray-600">{{ $log->model_type }}</span>
                        </p>
                        <span class="text-xs font-bold text-gray-400">{{ $log->created_at->diffForHumans() }}</span>
                    </div>
                    <p class="text-xs text-gray-500 mt-1">{{ $log->description }}</p>
                    <p class="text-[10px] text-gray-400 mt-2 font-mono">IP: {{ $log->ip_address }}</p>
                </div>
                <a href="{{ route('admin.audit-logs.view', $log->ID) }}" class="text-gray-400 hover:text-blue-600"><i class="fas fa-chevron-right"></i></a>
            </div>
            @endforeach
        </div>
        <div class="mt-6">
            {{ $logs->links() }}
        </div>
    </div>
</div>
<script>
    $('#actionFilter, #adminFilter').change(function() {
        let action = $('#actionFilter').val();
        let admin = $('#adminFilter').val();
        window.location.href = `?action=${action}&admin_id=${admin}`;
    });
</script>
@endsection
