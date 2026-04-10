<div class="col-12">

    <div class="d-flex justify-content-between align-items-center action-bar mb-2">
        <div class="d-flex gap-2 align-items-center">
            <h5 class="mb-0">{{ __('customermodule::app.cleaningInfo') }}</h5>
        </div>
        @if (in_array(user()->permission('edit_clients'), ['all', 'added', 'both']))
            <button type="button" class="btn btn-primary btn-sm" id="editCleaningInfoBtn">
                <i class="fa fa-edit"></i> {{ __('app.edit') }}
            </button>
        @endif
    </div>

    {{-- View mode --}}
    <div id="cleaningInfoView" class="card card-body mb-4">
        <div class="row">
            <div class="col-md-6 mb-3">
                <label class="f-12 text-dark-grey fw-500">{{ __('customermodule::app.preferredCleaner') }}</label>
                <p class="mb-0">
                    {{ $cleaner?->name ?? '--' }}
                </p>
            </div>

            <div class="col-md-6 mb-3">
                <label class="f-12 text-dark-grey fw-500">{{ __('customermodule::app.clientTag') }}</label>
                <p class="mb-0">
                    @if ($detail?->client_tag)
                        <span class="badge badge-primary text-capitalize">{{ $detail->client_tag }}</span>
                    @else
                        --
                    @endif
                </p>
            </div>

            <div class="col-md-6 mb-3">
                <label class="f-12 text-dark-grey fw-500">{{ __('customermodule::app.keyHolding') }}</label>
                <p class="mb-0">
                    @if ($detail?->key_holding)
                        <span class="badge badge-success">{{ __('app.yes') }}</span>
                    @else
                        <span class="badge badge-secondary">{{ __('app.no') }}</span>
                    @endif
                </p>
            </div>

            <div class="col-md-6 mb-3">
                <label class="f-12 text-dark-grey fw-500">{{ __('customermodule::app.petInfo') }}</label>
                <p class="mb-0">{{ $detail?->pet_info ?? '--' }}</p>
            </div>

            <div class="col-md-6 mb-3">
                <label class="f-12 text-dark-grey fw-500">{{ __('customermodule::app.accessNotes') }}</label>
                <p class="mb-0">{{ $detail?->access_notes ?? '--' }}</p>
            </div>

            @if (in_array(user()->permission('edit_clients'), ['all', 'added', 'both']))
            <div class="col-md-6 mb-3">
                <label class="f-12 text-dark-grey fw-500">{{ __('customermodule::app.alarmCode') }}</label>
                <p class="mb-0">
                    @if ($detail?->alarm_code)
                        <span class="text-muted">&bull;&bull;&bull;&bull;&bull;&bull;</span>
                        <button type="button" class="btn btn-link btn-sm p-0 ml-1" id="toggleAlarmCode"
                            data-code="{{ $detail->alarm_code }}">{{ __('app.show') }}</button>
                    @else
                        --
                    @endif
                </p>
            </div>
            @endif
        </div>
    </div>

    {{-- Edit form (hidden by default) --}}
    @if (in_array(user()->permission('edit_clients'), ['all', 'added', 'both']))
    <div id="cleaningInfoEdit" class="card card-body mb-4 d-none">
        <form id="cleaningInfoForm">
            @csrf
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="f-14 fw-500">{{ __('customermodule::app.preferredCleaner') }}</label>
                    <select name="preferred_cleaner_id" class="form-control select-picker" data-live-search="true">
                        <option value="">-- {{ __('app.none') }} --</option>
                        @foreach (\App\Models\User::allEmployees() as $emp)
                            <option value="{{ $emp->id }}" @selected($detail?->preferred_cleaner_id == $emp->id)>
                                {{ $emp->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-6 mb-3">
                    <label class="f-14 fw-500">{{ __('customermodule::app.clientTag') }}</label>
                    <select name="client_tag" class="form-control select-picker">
                        <option value="">-- {{ __('app.none') }} --</option>
                        @foreach (['residential', 'commercial', 'strata', 'airbnb', 'vip'] as $tag)
                            <option value="{{ $tag }}" @selected($detail?->client_tag === $tag)>
                                {{ ucfirst($tag) }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-6 mb-3">
                    <label class="f-14 fw-500">{{ __('customermodule::app.keyHolding') }}</label>
                    <div class="d-flex align-items-center gap-2 mt-1">
                        <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" name="key_holding" id="keyHolding"
                                value="1" @checked($detail?->key_holding)>
                        </div>
                    </div>
                </div>

                <div class="col-md-6 mb-3">
                    <label class="f-14 fw-500">{{ __('customermodule::app.petInfo') }}</label>
                    <input type="text" name="pet_info" class="form-control"
                        value="{{ $detail?->pet_info }}" maxlength="255">
                </div>

                <div class="col-md-6 mb-3">
                    <label class="f-14 fw-500">{{ __('customermodule::app.alarmCode') }}</label>
                    <input type="password" name="alarm_code" class="form-control"
                        placeholder="{{ __('app.leaveBlankToKeep') }}" maxlength="255"
                        autocomplete="new-password">
                    <small class="text-muted">{{ __('customermodule::app.alarmCodeHint') }}</small>
                </div>

                <div class="col-md-6 mb-3">
                    <label class="f-14 fw-500">{{ __('customermodule::app.accessNotes') }}</label>
                    <textarea name="access_notes" class="form-control" rows="3"
                        maxlength="500">{{ $detail?->access_notes }}</textarea>
                </div>
            </div>

            <div class="d-flex gap-2 mt-2">
                <button type="submit" class="btn btn-primary btn-sm">{{ __('app.save') }}</button>
                <button type="button" class="btn btn-secondary btn-sm" id="cancelCleaningInfoEdit">{{ __('app.cancel') }}</button>
            </div>
        </form>
    </div>
    @endif

</div>

@push('scripts')
<script>
(function () {
    const updateUrl = '{{ route('fsm.clients.cleaning-info.update', $client->id) }}';

    $('#editCleaningInfoBtn').on('click', function () {
        $('#cleaningInfoView').addClass('d-none');
        $('#cleaningInfoEdit').removeClass('d-none');
    });

    $('#cancelCleaningInfoEdit').on('click', function () {
        $('#cleaningInfoEdit').addClass('d-none');
        $('#cleaningInfoView').removeClass('d-none');
    });

    $('#toggleAlarmCode').on('click', function () {
        const btn = $(this);
        const p   = btn.closest('p');
        if (btn.text() === '{{ __('app.show') }}') {
            p.find('span').text(btn.data('code'));
            btn.text('{{ __('app.hide') }}');
        } else {
            p.find('span').text('••••••');
            btn.text('{{ __('app.show') }}');
        }
    });

    $('#cleaningInfoForm').on('submit', function (e) {
        e.preventDefault();
        const formData = $(this).serialize();
        $.easyAjax({
            url:      updateUrl,
            type:     'POST',
            data:     formData + '&_method=POST',
            success: function (response) {
                if (response.status === 'success') {
                    window.toastr.success(response.message);
                    $('#cleaningInfoEdit').addClass('d-none');
                    $('#cleaningInfoView').removeClass('d-none');
                }
            }
        });
    });
}());
</script>
@endpush
