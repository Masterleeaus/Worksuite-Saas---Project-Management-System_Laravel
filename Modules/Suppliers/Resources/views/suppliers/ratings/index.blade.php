@extends('layouts.master')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h3 class="heading-h1">@lang('suppliers::app.menu.ratings')</h3>
        <a href="{{ route('suppliers.index') }}" class="btn btn-secondary">
            <i class="fa fa-arrow-left mr-1"></i> @lang('app.back')
        </a>
    </div>

    <div class="card">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead>
                        <tr>
                            <th>@lang('suppliers::app.menu.name')</th>
                            <th>@lang('app.email')</th>
                            <th>@lang('suppliers::app.menu.fsmRating')</th>
                            <th>@lang('suppliers::app.menu.leadTimeDays')</th>
                            <th>@lang('suppliers::app.menu.paymentTerms')</th>
                            <th>@lang('app.action')</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($suppliers as $supplier)
                        <tr>
                            <td>{{ $supplier->name }}</td>
                            <td>{{ $supplier->email }}</td>
                            <td>
                                @for($i = 1; $i <= 5; $i++)
                                    <i class="fa fa-star{{ $i <= $supplier->fsm_rating ? '' : '-o' }} text-warning"></i>
                                @endfor
                            </td>
                            <td>{{ $supplier->fsm_lead_time_days ?? '-' }}</td>
                            <td>{{ $supplier->fsm_payment_terms ?? '-' }}</td>
                            <td>
                                <button class="btn btn-sm btn-primary edit-rating"
                                    data-id="{{ $supplier->id }}"
                                    data-rating="{{ $supplier->fsm_rating }}"
                                    data-lead="{{ $supplier->fsm_lead_time_days }}"
                                    data-terms="{{ $supplier->fsm_payment_terms }}">
                                    <i class="fa fa-edit"></i>
                                </button>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="text-center">@lang('messages.noRecordFound')</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        @if($suppliers->hasPages())
        <div class="card-footer">
            {{ $suppliers->links() }}
        </div>
        @endif
    </div>
</div>

{{-- Edit Rating Modal --}}
<div class="modal fade" id="editRatingModal" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">@lang('suppliers::app.menu.editRating')</h5>
                <button type="button" class="close" data-dismiss="modal">
                    <span>&times;</span>
                </button>
            </div>
            <form id="editRatingForm" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="form-group">
                        <label>@lang('suppliers::app.menu.fsmRating')</label>
                        <select name="fsm_rating" class="form-control">
                            <option value="">-- @lang('app.select') --</option>
                            @for($i = 1; $i <= 5; $i++)
                            <option value="{{ $i }}">{{ $i }} ★</option>
                            @endfor
                        </select>
                    </div>
                    <div class="form-group">
                        <label>@lang('suppliers::app.menu.leadTimeDays')</label>
                        <input type="number" name="fsm_lead_time_days" class="form-control" min="0" max="365">
                    </div>
                    <div class="form-group">
                        <label>@lang('suppliers::app.menu.paymentTerms')</label>
                        <input type="text" name="fsm_payment_terms" class="form-control" maxlength="191">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">@lang('app.cancel')</button>
                    <button type="submit" class="btn btn-primary">@lang('app.save')</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
$(document).ready(function () {
    $('.edit-rating').on('click', function () {
        var id      = $(this).data('id');
        var rating  = $(this).data('rating');
        var lead    = $(this).data('lead');
        var terms   = $(this).data('terms');
        var url     = "{{ route('suppliers.rating.update', ['id' => ':id']) }}".replace(':id', id);

        $('#editRatingForm').attr('action', url);
        $('[name=fsm_rating]').val(rating);
        $('[name=fsm_lead_time_days]').val(lead);
        $('[name=fsm_payment_terms]').val(terms);

        $('#editRatingModal').modal('show');
    });
});
</script>
@endpush
