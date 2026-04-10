@extends('admin.layouts.app')

@section('title', 'Manage Coupons')

@section('content')
<div class="premium-table-card">
    <div class="px-8 py-6 border-b border-gray-100 flex items-center justify-between bg-gray-50/50">
        <h2 class="text-xl font-black text-gray-900">Coupons & Discounts</h2>
        @if(validatePermissions('admin/coupons/add'))
        <a href="{{ route('admin.coupons.add') }}" class="px-4 py-2 bg-blue-600 text-white rounded-lg text-xs font-bold uppercase tracking-widest hover:bg-blue-700 transition-colors shadow-lg shadow-blue-500/30">
            Create Coupon
        </a>
        @endif
    </div>
    
    <div class="p-6">
        <table id="couponsTable" class="w-full text-left stripe hover">
            <thead>
                <tr>
                    <th class="px-4 py-3">Code</th>
                    <th class="px-4 py-3">Discount</th>
                    <th class="px-4 py-3">Usage</th>
                    <th class="px-4 py-3">Status</th>
                    <th class="px-4 py-3">Valid Until</th>
                    <th class="px-4 py-3">Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($coupons as $coupon)
                <tr class="group hover:bg-blue-50/30 transition-colors">
                    <td class="px-4 py-4">
                        <div class="flex flex-col">
                            <span class="font-black text-gray-900 font-mono tracking-wider">{{ $coupon->code }}</span>
                            <span class="text-[10px] text-gray-400 font-bold uppercase">{{ $coupon->name }}</span>
                        </div>
                    </td>
                    <td class="px-4 py-4">
                        <span class="inline-flex items-center px-3 py-1 rounded-lg text-xs font-black bg-blue-50 text-blue-600 border border-blue-100">
                            {{ $coupon->formatted_discount }}
                        </span>
                    </td>
                    <td class="px-4 py-4">
                        <div class="flex items-center gap-2">
                            <div class="w-24 h-2 bg-gray-100 rounded-full overflow-hidden">
                                <div class="h-full bg-blue-500" style="width: {{ $coupon->max_uses ? min(($coupon->used_count / $coupon->max_uses) * 100, 100) : 0 }}%"></div>
                            </div>
                            <span class="text-xs font-bold text-gray-500">{{ $coupon->used_count }} / {{ $coupon->max_uses ?? '∞' }}</span>
                        </div>
                    </td>
                    <td class="px-4 py-4">
                        <span class="status-badge inline-flex items-center gap-2 px-3 py-1 rounded-lg text-[10px] font-black uppercase tracking-widest
                            {{ $coupon->status === 'active' ? 'bg-emerald-50 text-emerald-600 border border-emerald-100' : '' }}
                            {{ $coupon->status === 'expired' ? 'bg-gray-50 text-gray-600 border border-gray-100' : '' }}
                            {{ $coupon->status === 'inactive' ? 'bg-red-50 text-red-600 border border-red-100' : '' }}
                        ">
                            <span class="w-2 h-2 rounded-full 
                                {{ $coupon->status === 'active' ? 'bg-emerald-500' : '' }}
                                {{ $coupon->status === 'expired' ? 'bg-gray-500' : '' }}
                                {{ $coupon->status === 'inactive' ? 'bg-red-500' : '' }}
                            "></span>
                            {{ $coupon->status }}
                        </span>
                    </td>
                    <td class="px-4 py-4 text-xs font-bold text-gray-500">
                        {{ $coupon->expires_at ? $coupon->expires_at->format('M d, Y') : 'Never' }}
                    </td>
                    <td class="px-4 py-4">
                        <div class="flex items-center justify-center gap-2">
                            @if(validatePermissions('admin/coupons/edit/{id}'))
                            <a href="{{ route('admin.coupons.edit', $coupon->ID) }}" class="w-8 h-8 rounded-lg bg-blue-50 text-blue-600 hover:bg-blue-600 hover:text-white flex items-center justify-center transition-all">
                                <i class="fas fa-edit text-xs"></i>
                            </a>
                            @endif
                            {{-- @if (validatePermissions('admin/coupons/delete/{id}'))
                            <button onclick="toggleCouponStatus({{ $coupon->ID }})" class="w-8 h-8 rounded-lg bg-amber-50 text-amber-600 hover:bg-amber-500 hover:text-white flex items-center justify-center transition-all">
                                <i class="fas fa-power-off text-xs"></i>
                            </button>
                            @endif --}}
                            @if(validatePermissions('admin/coupons/delete/{id}'))
                            <button onclick="deleteCoupon({{ $coupon->ID }})" class="w-8 h-8 rounded-lg bg-red-50 text-red-600 hover:bg-red-600 hover:text-white flex items-center justify-center transition-all">
                                <i class="fas fa-trash text-xs"></i>
                            </button>
                            @endif
                        </div>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection

@section('scripts')
<script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/responsive/2.5.0/js/dataTables.responsive.min.js"></script>
<script>
    $(document).ready(function() {
        $('#couponsTable').DataTable({
            responsive: true,
            language: { searchPlaceholder: "Search coupons..." }
        });
    });

    function toggleCouponStatus(id) {
        $.ajax({
            url: `/admin/coupons/toggle-status/${id}`,
            type: 'POST',
            headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
            success: (res) => {
                if(res.success) location.reload();
            }
        });
    }

    function deleteCoupon(id) {
        Swal.fire({
            title: 'Delete Coupon?',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#ef4444',
            confirmButtonText: 'Delete'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: `/admin/coupons/delete/${id}`,
                    type: 'GET',
                    success: (res) => {
                        if(res.success) Swal.fire('Deleted!', '', 'success').then(() => location.reload());
                    }
                });
            }
        });
    }
</script>
@endsection
