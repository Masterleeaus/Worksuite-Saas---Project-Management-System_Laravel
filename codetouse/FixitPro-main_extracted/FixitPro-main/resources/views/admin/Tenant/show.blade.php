@extends('admin.layouts.app')
@section('title', 'Business Details')
@section('content')
<div class="max-w-5xl mx-auto">
    <div class="mb-6"><a href="{{ route('admin.tenants') }}" class="text-gray-400 hover:text-blue-600 font-bold"><i class="fas fa-arrow-left"></i> Back to List</a></div>
    
    {{-- Header Card --}}
    <div class="bg-white rounded-3xl shadow-xl overflow-hidden border border-gray-100 mb-8">
        <div class="p-8 flex items-start justify-between">
            <div class="flex items-center gap-6">
                <div class="w-20 h-20 rounded-2xl bg-gradient-to-br from-blue-600 to-indigo-600 text-white flex items-center justify-center text-3xl font-black shadow-lg shadow-blue-500/30">
                    {{ substr($tenant->business_name, 0, 1) }}
                </div>
                <div>
                    <h1 class="text-3xl font-black text-gray-900 mb-1">{{ $tenant->business_name }}</h1>
                    <div class="flex items-center gap-4 text-sm font-medium text-gray-500">
                        <span class="flex items-center gap-2"><i class="fas fa-user-circle"></i> {{ $tenant->owner_name }}</span>
                        <span class="flex items-center gap-2"><i class="fas fa-envelope"></i> {{ $tenant->email }}</span>
                    </div>
                </div>
            </div>
            <div class="flex flex-col items-end gap-3">
                <span class="px-4 py-2 rounded-xl text-xs font-black uppercase tracking-widest bg-{{ $tenant->status_badge_color }}-50 text-{{ $tenant->status_badge_color }}-600 border border-{{ $tenant->status_badge_color }}-100">
                    {{ $tenant->status }}
                </span>
                <span class="text-xs font-bold text-gray-400">Joined {{ $tenant->created_at->format('M d, Y') }}</span>
            </div>
        </div>
        
        <div class="px-8 py-4 bg-gray-50 border-t border-gray-100 grid grid-cols-4 gap-4">
            <div class="p-4 bg-white rounded-xl border border-gray-100 text-center">
                <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest">Plan</p>
                <p class="text-lg font-black text-blue-600 capitalize">{{ $tenant->plan_type }}</p>
            </div>
            <div class="p-4 bg-white rounded-xl border border-gray-100 text-center">
                <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest">Workers</p>
                <p class="text-lg font-black text-gray-900">{{ $tenant->workers_count ?? 0 }}</p>
            </div>
            <div class="p-4 bg-white rounded-xl border border-gray-100 text-center">
                <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest">Clients</p>
                <p class="text-lg font-black text-gray-900">{{ $tenant->clients_count ?? 0 }}</p>
            </div>
            <div class="p-4 bg-white rounded-xl border border-gray-100 text-center">
                <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest">Job Volume</p>
                <p class="text-lg font-black text-gray-900">{{ $tenant->jobs_count ?? 0 }}</p>
            </div>
        </div>
    </div>

    {{-- Subscriptions Table --}}
    <div class="premium-table-card mb-8">
        <div class="px-8 py-6 border-b border-gray-100"><h2 class="text-lg font-black text-gray-900">Subscription History</h2></div>
        <div class="p-6">
            <table class="w-full text-left">
                <thead>
                    <tr class="text-xs text-gray-400 uppercase border-b border-gray-100">
                        <th class="px-4 py-3">Plan</th>
                        <th class="px-4 py-3">Amount</th>
                        <th class="px-4 py-3">Invoiced</th>
                        <th class="px-4 py-3">Status</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($tenant->subscriptions as $sub)
                    <tr>
                        <td class="px-4 py-4 font-bold">{{ $sub->plan_name }}</td>
                        <td class="px-4 py-4 font-mono">${{ number_format($sub->price, 2) }}</td>
                        <td class="px-4 py-4 text-sm text-gray-500">{{ $sub->created_at->format('M d, Y') }}</td>
                        <td class="px-4 py-4">
                            <span class="text-xs font-bold text-emerald-600 uppercase">{{ $sub->status }}</span>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="4" class="px-4 py-8 text-center text-gray-400 font-bold">No subscription history</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
