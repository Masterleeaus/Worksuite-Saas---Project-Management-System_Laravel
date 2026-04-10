@extends('admin.layouts.app')
@section('title', 'Create Coupon')
@section('content')
<div class="max-w-3xl mx-auto">
    <div class="mb-6"><a href="{{ route('admin.coupons') }}" class="text-gray-400 hover:text-blue-600 font-bold"><i class="fas fa-arrow-left"></i> Back</a></div>
    <div class="bg-white rounded-3xl shadow-xl overflow-hidden border border-gray-100">
        <div class="p-8 border-b border-gray-100 bg-gray-50/50"><h1 class="text-xl font-black text-gray-900">Create Coupon</h1></div>
        <form action="{{ route('admin.coupons.store') }}" method="POST" class="p-8 space-y-6">
            @csrf
            <div class="grid grid-cols-2 gap-6">
                <div class="col-span-2">
                    <label class="block text-xs font-bold text-gray-500 uppercase tracking-widest mb-2">Coupon Code</label>
                    <div class="flex gap-2">
                        <input type="text" name="code" id="code" value="{{ $generatedCode }}" class="flex-1 px-4 py-3 rounded-xl border-gray-200 font-mono font-bold text-lg uppercase" required>
                        <button type="button" onclick="generateCode()" class="px-4 py-2 bg-gray-100 rounded-xl font-bold text-gray-600 hover:bg-gray-200"><i class="fas fa-random"></i></button>
                    </div>
                </div>
                <div class="col-span-2">
                    <label class="block text-xs font-bold text-gray-500 uppercase tracking-widest mb-2">Internal Name</label>
                    <input type="text" name="name" class="w-full px-4 py-3 rounded-xl border-gray-200 font-bold" placeholder="e.g. Summer Sale 2026" required>
                </div>
                <div>
                    <label class="block text-xs font-bold text-gray-500 uppercase tracking-widest mb-2">Discount Type</label>
                    <select name="discount_type" class="w-full px-4 py-3 rounded-xl border-gray-200 font-bold">
                        <option value="percentage">Percentage (%)</option>
                        <option value="fixed">Fixed Amount ($)</option>
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-bold text-gray-500 uppercase tracking-widest mb-2">Discount Value</label>
                    <input type="number" step="0.01" name="discount_value" class="w-full px-4 py-3 rounded-xl border-gray-200 font-bold" required>
                </div>
                <div>
                   <label class="block text-xs font-bold text-gray-500 uppercase tracking-widest mb-2">Start Date</label>
                   <input type="date" name="starts_at" class="w-full px-4 py-3 rounded-xl border-gray-200 font-bold">
                </div>
                <div>
                   <label class="block text-xs font-bold text-gray-500 uppercase tracking-widest mb-2">Expiry Date</label>
                   <input type="date" name="expires_at" class="w-full px-4 py-3 rounded-xl border-gray-200 font-bold">
                </div>
                <div>
                    <label class="block text-xs font-bold text-gray-500 uppercase tracking-widest mb-2">Max Uses (Total)</label>
                    <input type="number" name="max_uses" class="w-full px-4 py-3 rounded-xl border-gray-200 font-bold" placeholder="Unlimited">
                </div>
                <div>
                    <label class="block text-xs font-bold text-gray-500 uppercase tracking-widest mb-2">Max Uses (Per User)</label>
                    <input type="number" name="max_uses_per_user" class="w-full px-4 py-3 rounded-xl border-gray-200 font-bold" value="1">
                </div>
            </div>
            <div class="pt-6 border-t border-gray-100 flex justify-end">
                <button type="submit" class="px-8 py-3 bg-blue-600 text-white rounded-xl font-bold hover:shadow-lg hover:-translate-y-0.5 transition-all">Create Coupon</button>
            </div>
        </form>
    </div>
</div>
<script>
function generateCode() {
    $.get("{{ route('admin.coupons.generate-code') }}", function(data) {
        $('#code').val(data.code);
    });
}
</script>
@endsection
