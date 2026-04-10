@extends('admin.layouts.app')

@section('title', 'Subscription Plans')

@section('content')
<div class="bg-gradient-to-r from-blue-700 to-blue-500 text-white rounded-3xl shadow-2xl p-10 mb-10 relative overflow-hidden">
    <div class="relative z-10">
        <div class="flex items-center gap-3 mb-4">
            <div class="w-3 h-3 rounded-full bg-emerald-400 animate-pulse"></div>
            <span class="text-emerald-300 text-xs font-bold uppercase tracking-widest">Monetization</span>
        </div>
        <h1 class="text-4xl font-black mb-3 tracking-tight">Subscription Plans</h1>
        <p class="text-blue-100 text-lg opacity-80">Manage pricing tiers and feature limits.</p>
    </div>
    <div class="absolute right-0 top-0 bottom-0 opacity-10 flex items-center p-8 pointer-events-none">
        <i class="fas fa-tags text-[180px] -rotate-12"></i>
    </div>
</div>

<div class="mb-8 flex justify-end">
    @if(validatePermissions('admin/plans/add'))
    <a href="{{ route('admin.plans.add') }}" class="group relative inline-flex items-center gap-2 px-6 py-3 bg-blue-600 text-white rounded-xl font-bold shadow-lg shadow-blue-500/30 hover:shadow-blue-500/50 hover:scale-105 transition-all duration-300">
        <i class="fas fa-plus group-hover:rotate-90 transition-transform duration-300"></i>
        <span>Create New Plan</span>
    </a>
    @endif
</div>

<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
    @foreach($plans as $plan)
    <div class="bg-white rounded-3xl border border-gray-100 shadow-sm hover:shadow-xl hover:-translate-y-1 transition-all duration-300 relative overflow-hidden group">
        @if($plan->is_popular)
        <div class="absolute top-0 right-0">
            <div class="bg-amber-500 text-white text-[10px] font-black uppercase tracking-widest py-1 px-8 rotate-45 translate-x-8 translate-y-4 shadow-sm">
                Popular
            </div>
        </div>
        @endif
        
        <div class="p-8">
            <div class="flex items-center justify-between mb-4">
                <span class="inline-flex items-center gap-2 px-3 py-1 rounded-lg text-[10px] font-black uppercase tracking-widest
                    {{ !$plan->is_active ? 'bg-red-50 text-red-600' : 'bg-emerald-50 text-emerald-600' }}">
                    <span class="w-2 h-2 rounded-full {{ !$plan->is_active ? 'bg-red-500' : 'bg-emerald-500' }}"></span>
                    {{ $plan->is_active ? 'Active' : 'Inactive' }}
                </span>
                <div class="flex gap-2 opacity-0 group-hover:opacity-100 transition-opacity">
                    @if(validatePermissions('admin/plans/edit/{id}'))
                    <a href="{{ route('admin.plans.edit', $plan->ID) }}" class="w-8 h-8 rounded-lg bg-gray-50 text-gray-500 hover:bg-blue-500 hover:text-white flex items-center justify-center transition-colors">
                        <i class="fas fa-edit text-xs"></i>
                    </a>
                    @endif
                    @if(validatePermissions('admin/plans/delete/{id}'))
                    <button onclick="deletePlan({{ $plan->ID }})" class="w-8 h-8 rounded-lg bg-gray-50 text-gray-500 hover:bg-red-500 hover:text-white flex items-center justify-center transition-colors">
                        <i class="fas fa-trash text-xs"></i>
                    </button>
                    @endif
                </div>
            </div>

            <h3 class="text-2xl font-black text-gray-900 mb-2">{{ $plan->name }}</h3>
            <div class="flex items-baseline gap-1 mb-6">
                <span class="text-4xl font-black text-blue-600">${{ number_format($plan->price, 0) }}</span>
                <span class="text-gray-400 font-bold">/{{ $plan->billing_cycle === 'monthly' ? 'mo' : 'yr' }}</span>
            </div>

            <div class="space-y-3 mb-8">
                <div class="flex items-center gap-3 text-sm font-bold text-gray-600">
                    <i class="fas fa-users text-blue-400 w-5"></i>
                    <span>{{ $plan->worker_limit }} Workers Limit</span>
                </div>
                <div class="flex items-center gap-3 text-sm font-bold text-gray-600">
                    <i class="fas fa-briefcase text-blue-400 w-5"></i>
                    <span>{{ $plan->job_limit ?? 'Unlimited' }} Jobs / Month</span>
                </div>
                @if($plan->features)
                    @foreach($plan->features as $feature)
                    <div class="flex items-center gap-3 text-sm font-medium text-gray-500">
                        <i class="fas fa-check text-emerald-400 w-5"></i>
                        <span>{{ $feature }}</span>
                    </div>
                    @endforeach
                @endif
            </div>

            <div class="pt-6 border-t border-gray-100 flex items-center justify-between">
                <span class="text-xs font-bold text-gray-400 uppercase tracking-wider">Subscribers</span>
                <span class="text-sm font-black text-gray-900">{{ $plan->subscriptions_count ?? 0 }}</span>
            </div>
        </div>
    </div>
    @endforeach
</div>
@endsection

@section('scripts')
<script>
function deletePlan(id) {
    Swal.fire({
        title: 'Delete Plan?',
        text: 'This action cannot be undone.',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#ef4444',
        cancelButtonColor: '#64748b',
        confirmButtonText: 'Yes, delete it!'
    }).then((result) => {
        if (result.isConfirmed) {
            $.ajax({
                url: `/admin/plans/delete/${id}`,
                type: 'GET',
                success: function(response) {
                    Swal.fire('Deleted!', response.message, 'success').then(() => location.reload());
                }
            });
        }
    });
}
</script>
@endsection
