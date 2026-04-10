@php
    $canManage = (user()->permission('manage_provider_compliance') === 'all');
    $d = $employeeDetail;
@endphp

<div class="col-xl-12">
    <div class="card mb-4">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h4 class="card-title mb-0">@lang('FSM Compliance')</h4>
            @if($canManage)
                <button class="btn btn-primary btn-sm" id="saveComplianceBtn">@lang('app.save')</button>
            @endif
        </div>
        <div class="card-body">
            <form id="complianceForm">
                @csrf
                <div class="row">
                    <div class="col-md-4 mb-3">
                        <label class="f-14 text-dark-grey fw-500">@lang('Police Check Date')</label>
                        <input type="date" name="police_check_date" class="form-control"
                               value="{{ optional($d)->police_check_date }}"
                               @if(!$canManage) readonly @endif>
                    </div>
                    <div class="col-md-4 mb-3">
                        <label class="f-14 text-dark-grey fw-500">@lang('Police Check Expiry')</label>
                        <input type="date" name="police_check_expiry" class="form-control"
                               value="{{ optional($d)->police_check_expiry }}"
                               @if(!$canManage) readonly @endif>
                        @if(optional($d)->police_check_expiry && $d->police_check_expiry < now()->toDateString())
                            <span class="badge badge-danger">@lang('Expired')</span>
                        @elseif(optional($d)->police_check_expiry && $d->police_check_expiry <= now()->addDays(30)->toDateString())
                            <span class="badge badge-warning">@lang('Expiring Soon')</span>
                        @endif
                    </div>
                    <div class="col-md-4 mb-3">
                        <label class="f-14 text-dark-grey fw-500">@lang('Insurance Expiry')</label>
                        <input type="date" name="insurance_expiry" class="form-control"
                               value="{{ optional($d)->insurance_expiry }}"
                               @if(!$canManage) readonly @endif>
                        @if(optional($d)->insurance_expiry && $d->insurance_expiry < now()->toDateString())
                            <span class="badge badge-danger">@lang('Expired')</span>
                        @elseif(optional($d)->insurance_expiry && $d->insurance_expiry <= now()->addDays(30)->toDateString())
                            <span class="badge badge-warning">@lang('Expiring Soon')</span>
                        @endif
                    </div>
                    <div class="col-md-4 mb-3">
                        <label class="f-14 text-dark-grey fw-500">@lang('WWCC Expiry')</label>
                        <input type="date" name="wwcc_expiry" class="form-control"
                               value="{{ optional($d)->wwcc_expiry }}"
                               @if(!$canManage) readonly @endif>
                        @if(optional($d)->wwcc_expiry && $d->wwcc_expiry < now()->toDateString())
                            <span class="badge badge-danger">@lang('Expired')</span>
                        @elseif(optional($d)->wwcc_expiry && $d->wwcc_expiry <= now()->addDays(30)->toDateString())
                            <span class="badge badge-warning">@lang('Expiring Soon')</span>
                        @endif
                    </div>
                    <div class="col-md-4 mb-3">
                        <label class="f-14 text-dark-grey fw-500">@lang('ABN (Australian Business Number)')</label>
                        <input type="text" name="abn" class="form-control" maxlength="20"
                               value="{{ optional($d)->abn }}"
                               @if(!$canManage) readonly @endif>
                    </div>
                    <div class="col-md-2 mb-3">
                        <label class="f-14 text-dark-grey fw-500">@lang('Max Jobs / Day')</label>
                        <input type="number" name="max_jobs_per_day" class="form-control" min="1" max="20"
                               value="{{ optional($d)->max_jobs_per_day ?? 4 }}"
                               @if(!$canManage) readonly @endif>
                    </div>
                    <div class="col-md-2 mb-3 d-flex align-items-center">
                        <div class="form-check mt-4">
                            <input type="checkbox" name="is_subcontractor" id="isSubcontractor" class="form-check-input"
                                   value="1" {{ optional($d)->is_subcontractor ? 'checked' : '' }}
                                   @if(!$canManage) disabled @endif>
                            <label class="form-check-label f-14 text-dark-grey fw-500" for="isSubcontractor">
                                @lang('Is Subcontractor')
                            </label>
                        </div>
                    </div>
                    @if(optional($d)->star_rating)
                    <div class="col-md-4 mb-3">
                        <label class="f-14 text-dark-grey fw-500">@lang('Star Rating')</label>
                        <div class="f-16 text-warning">
                            @for($i = 1; $i <= 5; $i++)
                                <i class="{{ $i <= round($d->star_rating) ? 'fa fa-star' : 'fa fa-star-o' }}"></i>
                            @endfor
                            <span class="text-dark-grey f-12 ml-1">({{ number_format($d->star_rating, 2) }})</span>
                        </div>
                    </div>
                    @endif
                </div>
            </form>
        </div>
    </div>
</div>

@if($canManage)
@push('scripts')
<script>
    $('#saveComplianceBtn').on('click', function () {
        var form = $('#complianceForm');
        var data = form.serializeArray();
        // Handle checkbox: ensure unchecked state is also sent
        if (!$('#isSubcontractor').is(':checked')) {
            data.push({ name: 'is_subcontractor', value: '0' });
        }
        $.ajax({
            url: '{{ route("provider.compliance.update", $employee->id) }}',
            method: 'POST',
            data: data,
            success: function (res) {
                window.toastr.success(res.message || 'Saved.');
            },
            error: function (xhr) {
                window.toastr.error('Failed to save compliance data.');
            }
        });
    });
</script>
@endpush
@endif
