@extends('admin.layouts.app')
@section('title', 'Edit Coupon')
@section('content')
<div class="max-w-3xl mx-auto">
    <div class="mb-6"><a href="{{ route('admin.coupons') }}" class="text-gray-400 hover:text-blue-600 font-bold"><i class="fas fa-arrow-left"></i> Back</a></div>
    <div class="bg-white rounded-3xl shadow-xl overflow-hidden border border-gray-100">
        <div class="p-8 border-b border-gray-100 bg-gray-50/50"><h1 class="text-xl font-black text-gray-900">Edit Coupon: {{ $coupon->code }}</h1></div>
        <form action="{{ route('admin.coupons.update', $coupon->ID) }}" method="POST" class="p-8 space-y-6">
            @csrf
            
            <div class="grid grid-cols-2 gap-6">
                <div class="col-span-2">
                    <label class="block text-xs font-bold text-gray-500 uppercase tracking-widest mb-2">Coupon Code</label>
                    <input type="text" name="code" value="{{ $coupon->code }}" class="w-full px-4 py-3 rounded-xl border-gray-200 font-mono font-bold text-lg uppercase" required>
                </div>
                <div class="col-span-2">
                    <label class="block text-xs font-bold text-gray-500 uppercase tracking-widest mb-2">Internal Name</label>
                    <input type="text" name="name" value="{{ $coupon->name }}" class="w-full px-4 py-3 rounded-xl border-gray-200 font-bold" required>
                </div>
                <div>
                    <label class="block text-xs font-bold text-gray-500 uppercase tracking-widest mb-2">Discount Type</label>
                    <select name="discount_type" class="w-full px-4 py-3 rounded-xl border-gray-200 font-bold">
                        <option value="percentage" {{ $coupon->discount_type == 'percentage' ? 'selected' : '' }}>Percentage (%)</option>
                        <option value="fixed" {{ $coupon->discount_type == 'fixed' ? 'selected' : '' }}>Fixed Amount ($)</option>
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-bold text-gray-500 uppercase tracking-widest mb-2">Discount Value</label>
                    <input type="number" step="0.01" name="discount_value" value="{{ $coupon->discount_value }}" class="w-full px-4 py-3 rounded-xl border-gray-200 font-bold" required>
                </div>
                <div>
                   <label class="block text-xs font-bold text-gray-500 uppercase tracking-widest mb-2">Start Date</label>
                   <input type="date" name="starts_at" value="{{ $coupon->starts_at?->format('Y-m-d') }}" class="w-full px-4 py-3 rounded-xl border-gray-200 font-bold">
                </div>
                <div>
                   <label class="block text-xs font-bold text-gray-500 uppercase tracking-widest mb-2">Expiry Date</label>
                   <input type="date" name="expires_at" value="{{ $coupon->expires_at?->format('Y-m-d') }}" class="w-full px-4 py-3 rounded-xl border-gray-200 font-bold">
                </div>
                <div>
                    <label class="block text-xs font-bold text-gray-500 uppercase tracking-widest mb-2">Max Uses (Total)</label>
                    <input type="number" name="max_uses" value="{{ $coupon->max_uses }}" class="w-full px-4 py-3 rounded-xl border-gray-200 font-bold" placeholder="Unlimited">
                </div>
                <div>
                    <label class="block text-xs font-bold text-gray-500 uppercase tracking-widest mb-2">Max Uses (Per User)</label>
                    <input type="number" name="max_uses_per_user" value="{{ $coupon->max_uses_per_user }}" class="w-full px-4 py-3 rounded-xl border-gray-200 font-bold">
                </div>
                <div class="col-span-2">
                    <label class="flex items-center gap-3 cursor-pointer">
                        <input type="checkbox" name="is_active" value="1" {{ $coupon->is_active ? 'checked' : '' }} class="w-5 h-5 rounded text-blue-600">
                        <span class="font-bold text-gray-700">Active Coupon</span>
                    </label>
                </div>
            </div>
            <div class="pt-6 border-t border-gray-100 flex justify-end">
                <button type="submit" class="px-8 py-3 bg-blue-600 text-white rounded-xl font-bold hover:shadow-lg hover:-translate-y-0.5 transition-all">Update Coupon</button>
            </div>
        </form>
    </div>
</div>
@endsection
