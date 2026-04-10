@extends('admin.layouts.app')
@section('title', 'Edit Business')
@section('content')
<div class="max-w-3xl mx-auto">
    <div class="mb-6"><a href="{{ route('admin.tenants') }}" class="text-gray-400 hover:text-blue-600 font-bold"><i class="fas fa-arrow-left"></i> Back to List</a></div>
    
    <div class="bg-white rounded-3xl shadow-xl overflow-hidden border border-gray-100">
        <div class="p-8 border-b border-gray-100 bg-gray-50/50"><h1 class="text-xl font-black text-gray-900">Edit Business Profile</h1></div>
        
        <form action="{{ route('admin.tenants.update', $tenant->ID) }}" method="POST" class="p-8 space-y-6">
            @csrf
            
            <div class="grid grid-cols-2 gap-6">
                <div class="col-span-2">
                    <label class="block text-xs font-bold text-gray-500 uppercase tracking-widest mb-2">Business Name</label>
                    <input type="text" name="business_name" value="{{ $tenant->business_name }}" class="w-full px-4 py-3 rounded-xl border-gray-200 font-bold" required>
                </div>
                <div>
                    <label class="block text-xs font-bold text-gray-500 uppercase tracking-widest mb-2">Owner Name</label>
                    <input type="text" name="owner_name" value="{{ $tenant->owner_name }}" class="w-full px-4 py-3 rounded-xl border-gray-200 font-bold" required>
                </div>
                <div>
                    <label class="block text-xs font-bold text-gray-500 uppercase tracking-widest mb-2">Email Address</label>
                    <input type="email" name="email" value="{{ $tenant->email }}" class="w-full px-4 py-3 rounded-xl border-gray-200 font-bold" required>
                </div>
                <div>
                    <label class="block text-xs font-bold text-gray-500 uppercase tracking-widest mb-2">Phone</label>
                    <input type="text" name="phone" value="{{ $tenant->phone }}" class="w-full px-4 py-3 rounded-xl border-gray-200 font-bold">
                </div>
                <div>
                    <label class="block text-xs font-bold text-gray-500 uppercase tracking-widest mb-2">Plan Type</label>
                    <select name="plan_type" class="w-full px-4 py-3 rounded-xl border-gray-200 font-bold bg-white">
                        <option value="starter" {{ $tenant->plan_type == 'starter' ? 'selected' : '' }}>Starter</option>
                        <option value="professional" {{ $tenant->plan_type == 'professional' ? 'selected' : '' }}>Professional</option>
                        <option value="enterprise" {{ $tenant->plan_type == 'enterprise' ? 'selected' : '' }}>Enterprise</option>
                    </select>
                </div>
            </div>
            
            <div class="pt-6 border-t border-gray-100 flex justify-end">
                <button type="submit" class="px-8 py-3 bg-blue-600 text-white rounded-xl font-bold hover:shadow-lg hover:-translate-y-0.5 transition-all">Update Profile</button>
            </div>
        </form>
    </div>
</div>
@endsection
