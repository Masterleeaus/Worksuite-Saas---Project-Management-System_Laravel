@extends('admin.layouts.app')

@section('title', 'Create Plan')

@section('content')
<div class="max-w-3xl mx-auto">
    <div class="mb-6">
        <a href="{{ route('admin.plans') }}" class="inline-flex items-center gap-2 text-sm font-bold text-gray-400 hover:text-blue-600 transition-colors">
            <i class="fas fa-arrow-left"></i> Back to Plans
        </a>
    </div>

    <div class="bg-white rounded-3xl border border-gray-100 shadow-xl overflow-hidden">
        <div class="px-8 py-6 border-b border-gray-100 bg-gray-50/50">
            <h1 class="text-xl font-black text-gray-900">Create New Plan</h1>
            <p class="text-sm text-gray-500 font-medium">Define a new pricing tier for your customers.</p>
        </div>

        <form action="{{ route('admin.plans.store') }}" method="POST" class="p-8">
            @csrf
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
                <div class="col-span-2">
                    <label class="block text-xs font-bold text-gray-500 uppercase tracking-widest mb-2">Plan Name</label>
                    <input type="text" name="name" required class="w-full px-4 py-3 rounded-xl border-gray-200 focus:border-blue-500 focus:ring-blue-500 bg-gray-50 focus:bg-white transition-all font-bold text-gray-900" placeholder="e.g. Professional Plan">
                </div>

                <div>
                    <label class="block text-xs font-bold text-gray-500 uppercase tracking-widest mb-2">Price</label>
                    <div class="relative">
                        <span class="absolute left-4 top-3.5 text-gray-400 font-bold">$</span>
                        <input type="number" step="0.01" name="price" required class="w-full pl-8 pr-4 py-3 rounded-xl border-gray-200 focus:border-blue-500 focus:ring-blue-500 bg-gray-50 focus:bg-white transition-all font-bold text-gray-900" placeholder="0.00">
                    </div>
                </div>

                <div>
                    <label class="block text-xs font-bold text-gray-500 uppercase tracking-widest mb-2">Billing Cycle</label>
                    <select name="billing_cycle" class="w-full px-4 py-3 rounded-xl border-gray-200 focus:border-blue-500 focus:ring-blue-500 bg-gray-50 focus:bg-white transition-all font-bold text-gray-900">
                        <option value="monthly">Monthly</option>
                        <option value="yearly">Yearly</option>
                    </select>
                </div>

                <div>
                    <label class="block text-xs font-bold text-gray-500 uppercase tracking-widest mb-2">Worker Limit</label>
                    <input type="number" name="worker_limit" required class="w-full px-4 py-3 rounded-xl border-gray-200 focus:border-blue-500 focus:ring-blue-500 bg-gray-50 focus:bg-white transition-all font-bold text-gray-900" placeholder="5">
                </div>

                <div>
                    <label class="block text-xs font-bold text-gray-500 uppercase tracking-widest mb-2">Job Limit (Monthly)</label>
                    <input type="number" name="job_limit" class="w-full px-4 py-3 rounded-xl border-gray-200 focus:border-blue-500 focus:ring-blue-500 bg-gray-50 focus:bg-white transition-all font-bold text-gray-900" placeholder="Leave empty for unlimited">
                </div>

                <div class="col-span-2">
                    <label class="block text-xs font-bold text-gray-500 uppercase tracking-widest mb-2">Plan Features (One per line)</label>
                    <textarea name="features" rows="5" class="w-full px-4 py-3 rounded-xl border-gray-200 focus:border-blue-500 focus:ring-blue-500 bg-gray-50 focus:bg-white transition-all font-medium text-gray-900" placeholder="24/7 Support&#10;Advanced Analytics&#10;Custom Branding"></textarea>
                </div>

                <div class="col-span-2 flex items-center gap-8 pt-4 border-t border-gray-100">
                    <label class="flex items-center gap-3 cursor-pointer group">
                        <input type="checkbox" name="is_popular" value="1" class="w-5 h-5 rounded border-gray-300 text-blue-600 focus:ring-blue-500 transition-all">
                        <span class="text-sm font-bold text-gray-600 group-hover:text-blue-600 transition-colors">Mark as Popular</span>
                    </label>
                    <label class="flex items-center gap-3 cursor-pointer group">
                        <input type="checkbox" name="is_active" value="1" checked class="w-5 h-5 rounded border-gray-300 text-blue-600 focus:ring-blue-500 transition-all">
                        <span class="text-sm font-bold text-gray-600 group-hover:text-blue-600 transition-colors">Active Plan</span>
                    </label>
                </div>
            </div>

            <div class="flex items-center justify-end gap-4">
                <a href="{{ route('admin.plans') }}" class="px-6 py-3 rounded-xl text-sm font-bold text-gray-500 hover:bg-gray-50 transition-colors">Cancel</a>
                <button type="submit" class="px-8 py-3 bg-blue-600 text-white rounded-xl font-bold hover:bg-blue-700 hover:shadow-lg shadow-blue-500/30 transition-all transform hover:-translate-y-0.5">
                    Create Plan
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
