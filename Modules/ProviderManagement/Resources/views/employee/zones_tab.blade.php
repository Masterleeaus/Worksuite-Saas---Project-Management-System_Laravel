@php
    $canManage = (user()->permission('manage_provider_compliance') === 'all');
    $assignedIds = $employeeZones->pluck('zone_id')->toArray();
@endphp

<div class="col-xl-12">
    <div class="card mb-4">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h4 class="card-title mb-0">@lang('Service Zones')</h4>
            @if($canManage)
                <button class="btn btn-primary btn-sm" id="saveZonesBtn">@lang('app.save')</button>
            @endif
        </div>
        <div class="card-body">
            @if(empty($availableZones))
                <p class="text-muted">@lang('ZoneManagement module is not active. Install it to assign service zones.')</p>
            @else
                <form id="zonesForm">
                    @csrf
                    <div class="row">
                        @foreach($availableZones as $zone)
                            <div class="col-md-3 mb-3">
                                <div class="form-check">
                                    <input type="checkbox" name="zone_ids[]" id="zone_{{ $zone->id }}"
                                           class="form-check-input" value="{{ $zone->id }}"
                                           {{ in_array($zone->id, $assignedIds) ? 'checked' : '' }}
                                           @if(!$canManage) disabled @endif>
                                    <label class="form-check-label f-14 text-dark-grey" for="zone_{{ $zone->id }}">
                                        {{ $zone->name ?? 'Zone '.$zone->id }}
                                    </label>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </form>
            @endif
        </div>
    </div>
</div>

@if($canManage && !empty($availableZones))
@push('scripts')
<script>
    $('#saveZonesBtn').on('click', function () {
        var zoneIds = [];
        $('input[name="zone_ids[]"]:checked').each(function () {
            zoneIds.push($(this).val());
        });
        $.ajax({
            url: '{{ route("provider.zones.sync", $employee->id) }}',
            method: 'POST',
            data: { _token: '{{ csrf_token() }}', zone_ids: zoneIds },
            success: function (res) {
                window.toastr.success(res.message || 'Zones updated.');
            },
            error: function () {
                window.toastr.error('Failed to update zones.');
            }
        });
    });
</script>
@endpush
@endif
