<div class="col-12">

    <div class="d-flex justify-content-between align-items-center action-bar mb-2">
        <h5 class="mb-0">{{ __('customermodule::app.properties') }}</h5>
        @if (in_array(user()->permission('edit_clients'), ['all', 'added', 'both']))
            <button type="button" class="btn btn-primary btn-sm" data-toggle="modal" data-target="#addPropertyModal">
                <i class="fa fa-plus"></i> {{ __('app.add') }} {{ __('customermodule::app.property') }}
            </button>
        @endif
    </div>

    @if ($addresses->isEmpty())
        <div class="card card-body text-center text-muted py-5">
            <i class="fa fa-map-marker-alt fa-2x mb-2"></i>
            <p>{{ __('customermodule::app.noPropertiesYet') }}</p>
        </div>
    @else
        <div class="row">
            @foreach ($addresses as $address)
                <div class="col-md-6 mb-3">
                    <div class="card card-body h-100">
                        <div class="d-flex justify-content-between align-items-start">
                            <div>
                                <h6 class="mb-1">
                                    {{ $address->label ?: __('customermodule::app.property') }}
                                    @if ($address->is_primary)
                                        <span class="badge badge-success ml-1">{{ __('customermodule::app.primary') }}</span>
                                    @endif
                                </h6>
                                <p class="mb-1 text-muted f-13">{{ $address->full_address }}</p>
                            </div>
                            @if (in_array(user()->permission('edit_clients'), ['all', 'added', 'both']))
                                <div class="dropdown">
                                    <button class="btn btn-secondary btn-sm dropdown-toggle" data-toggle="dropdown">
                                        <i class="fa fa-ellipsis-v"></i>
                                    </button>
                                    <div class="dropdown-menu dropdown-menu-right">
                                        <a class="dropdown-item edit-property"
                                            href="javascript:;"
                                            data-id="{{ $address->id }}"
                                            data-client="{{ $client->id }}"
                                            data-address="{{ $address->toJson() }}">
                                            <i class="fa fa-edit mr-1"></i> {{ __('app.edit') }}
                                        </a>
                                        @if (in_array(user()->permission('delete_clients'), ['all', 'added', 'both']))
                                        <a class="dropdown-item text-danger delete-property"
                                            href="javascript:;"
                                            data-id="{{ $address->id }}"
                                            data-client="{{ $client->id }}">
                                            <i class="fa fa-trash mr-1"></i> {{ __('app.delete') }}
                                        </a>
                                        @endif
                                    </div>
                                </div>
                            @endif
                        </div>

                        <div class="mt-2 row f-12">
                            @if ($address->property_type)
                                <div class="col-6">
                                    <span class="text-dark-grey">{{ __('customermodule::app.propertyType') }}:</span>
                                    {{ ucfirst($address->property_type) }}
                                </div>
                            @endif
                            @if ($address->pet_info)
                                <div class="col-6">
                                    <span class="text-dark-grey">{{ __('customermodule::app.petInfo') }}:</span>
                                    {{ $address->pet_info }}
                                </div>
                            @endif
                            @if ($address->key_holding)
                                <div class="col-6">
                                    <span class="badge badge-info">{{ __('customermodule::app.keyHolding') }}</span>
                                </div>
                            @endif
                            @if ($address->special_instructions)
                                <div class="col-12 mt-1">
                                    <span class="text-dark-grey">{{ __('customermodule::app.specialInstructions') }}:</span>
                                    {{ $address->special_instructions }}
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @endif

</div>

{{-- Add / Edit Property Modal --}}
<div class="modal fade" id="addPropertyModal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="propertyModalTitle">{{ __('customermodule::app.addProperty') }}</h5>
                <button type="button" class="close" data-dismiss="modal">
                    <span>&times;</span>
                </button>
            </div>
            <form id="propertyForm">
                <div class="modal-body">
                    @csrf
                    <input type="hidden" id="propertyAddressId" name="_address_id" value="">

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="f-14 fw-500">{{ __('customermodule::app.label') }}</label>
                            <input type="text" name="label" id="propLabel" class="form-control" maxlength="100"
                                placeholder="{{ __('customermodule::app.labelPlaceholder') }}">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="f-14 fw-500">{{ __('customermodule::app.propertyType') }}</label>
                            <select name="property_type" id="propType" class="form-control select-picker">
                                <option value="">-- {{ __('app.select') }} --</option>
                                @foreach (['house', 'apartment', 'unit', 'office', 'commercial', 'strata'] as $type)
                                    <option value="{{ $type }}">{{ ucfirst($type) }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-12 mb-3">
                            <label class="f-14 fw-500">{{ __('app.address') }} <span class="text-danger">*</span></label>
                            <input type="text" name="address_line_1" id="propAddr1" class="form-control" required maxlength="255">
                        </div>
                        <div class="col-md-12 mb-3">
                            <input type="text" name="address_line_2" id="propAddr2" class="form-control"
                                placeholder="{{ __('customermodule::app.addressLine2') }}" maxlength="255">
                        </div>
                        <div class="col-md-4 mb-3">
                            <label class="f-14 fw-500">{{ __('customermodule::app.suburb') }}</label>
                            <input type="text" name="suburb" id="propSuburb" class="form-control" maxlength="100">
                        </div>
                        <div class="col-md-4 mb-3">
                            <label class="f-14 fw-500">{{ __('app.city') }}</label>
                            <input type="text" name="city" id="propCity" class="form-control" maxlength="100">
                        </div>
                        <div class="col-md-4 mb-3">
                            <label class="f-14 fw-500">{{ __('app.state') }}</label>
                            <input type="text" name="state" id="propState" class="form-control" maxlength="100">
                        </div>
                        <div class="col-md-4 mb-3">
                            <label class="f-14 fw-500">{{ __('app.postalCode') }}</label>
                            <input type="text" name="postal_code" id="propPostal" class="form-control" maxlength="20">
                        </div>
                        <div class="col-md-4 mb-3">
                            <label class="f-14 fw-500">{{ __('app.country') }}</label>
                            <input type="text" name="country" id="propCountry" class="form-control" maxlength="100">
                        </div>
                        <div class="col-md-4 mb-3">
                            <label class="f-14 fw-500">{{ __('customermodule::app.petInfo') }}</label>
                            <input type="text" name="pet_info" id="propPet" class="form-control" maxlength="255">
                        </div>
                        <div class="col-md-12 mb-3">
                            <label class="f-14 fw-500">{{ __('customermodule::app.specialInstructions') }}</label>
                            <textarea name="special_instructions" id="propInstructions" class="form-control" rows="2"></textarea>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label class="f-14 fw-500">{{ __('customermodule::app.accessNotes') }}</label>
                            <input type="text" name="access_notes" id="propAccess" class="form-control" maxlength="500">
                        </div>
                        <div class="col-md-4 mb-3">
                            <label class="f-14 fw-500">{{ __('customermodule::app.alarmCode') }}</label>
                            <input type="password" name="alarm_code" id="propAlarm" class="form-control"
                                placeholder="{{ __('app.leaveBlankToKeep') }}" maxlength="255" autocomplete="new-password">
                        </div>
                        <div class="col-md-2 mb-3 d-flex flex-column">
                            <label class="f-14 fw-500">{{ __('customermodule::app.keyHolding') }}</label>
                            <div class="form-check form-switch mt-auto">
                                <input class="form-check-input" type="checkbox" name="key_holding" id="propKey" value="1">
                            </div>
                        </div>
                        <div class="col-md-2 mb-3 d-flex flex-column">
                            <label class="f-14 fw-500">{{ __('customermodule::app.primary') }}</label>
                            <div class="form-check form-switch mt-auto">
                                <input class="form-check-input" type="checkbox" name="is_primary" id="propPrimary" value="1">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary btn-sm" data-dismiss="modal">{{ __('app.cancel') }}</button>
                    <button type="submit" class="btn btn-primary btn-sm">{{ __('app.save') }}</button>
                </div>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script>
(function () {
    const clientId  = {{ $client->id }};
    const storeUrl  = '{{ route('fsm.clients.properties.store', $client->id) }}';
    const destroyBase = '/account/clients/' + clientId + '/properties/';

    // Reset modal on open
    $('#addPropertyModal').on('show.bs.modal', function () {
        $('#propertyModalTitle').text('{{ __('customermodule::app.addProperty') }}');
        $('#propertyAddressId').val('');
        $('#propertyForm')[0].reset();
    });

    // Edit — populate modal
    $('body').on('click', '.edit-property', function () {
        const addr = $(this).data('address');
        $('#propertyModalTitle').text('{{ __('customermodule::app.editProperty') }}');
        $('#propertyAddressId').val(addr.id);
        $('#propLabel').val(addr.label);
        $('#propType').val(addr.property_type).trigger('change');
        $('#propAddr1').val(addr.address_line_1);
        $('#propAddr2').val(addr.address_line_2);
        $('#propSuburb').val(addr.suburb);
        $('#propCity').val(addr.city);
        $('#propState').val(addr.state);
        $('#propPostal').val(addr.postal_code);
        $('#propCountry').val(addr.country);
        $('#propPet').val(addr.pet_info);
        $('#propInstructions').val(addr.special_instructions);
        $('#propAccess').val(addr.access_notes);
        $('#propKey').prop('checked', addr.key_holding);
        $('#propPrimary').prop('checked', addr.is_primary);
        $('#addPropertyModal').modal('show');
    });

    // Submit (add or update)
    $('#propertyForm').on('submit', function (e) {
        e.preventDefault();
        const addrId = $('#propertyAddressId').val();
        const url    = addrId ? (destroyBase + addrId) : storeUrl;
        const method = addrId ? 'PUT' : 'POST';

        $.easyAjax({
            url:     url,
            type:    method,
            data:    $(this).serialize(),
            success: function (response) {
                if (response.status === 'success') {
                    window.toastr.success(response.message);
                    $('#addPropertyModal').modal('hide');
                    // Reload tab content
                    $.easyAjax({
                        url:     '{{ route('fsm.clients.properties', $client->id) }}',
                        type:    'GET',
                        blockUI: true,
                        container: '.content-wrapper',
                        success: function (r) {
                            if (r.status === 'success') {
                                $('.content-wrapper').html(r.html);
                                init('.content-wrapper');
                            }
                        }
                    });
                }
            }
        });
    });

    // Delete
    $('body').on('click', '.delete-property', function () {
        const addrId   = $(this).data('id');
        const clientId = $(this).data('client');
        Swal.fire({
            title: '{{ __('messages.confirmDelete') }}',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: '{{ __('app.delete') }}',
        }).then(result => {
            if (result.isConfirmed) {
                $.easyAjax({
                    url:     destroyBase + addrId,
                    type:    'DELETE',
                    data:    { _token: '{{ csrf_token() }}' },
                    success: function (response) {
                        if (response.status === 'success') {
                            window.toastr.success(response.message);
                            $.easyAjax({
                                url:     '{{ route('fsm.clients.properties', $client->id) }}',
                                type:    'GET',
                                blockUI: true,
                                container: '.content-wrapper',
                                success: function (r) {
                                    if (r.status === 'success') {
                                        $('.content-wrapper').html(r.html);
                                        init('.content-wrapper');
                                    }
                                }
                            });
                        }
                    }
                });
            }
        });
    });
}());
</script>
@endpush
