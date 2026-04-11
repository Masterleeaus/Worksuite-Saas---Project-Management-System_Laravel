@extends('layouts.app')

@section('content')
    <div class="content-wrapper">
        <div class="d-flex justify-content-between align-items-center action-bar mb-3">
            <h4 class="mb-0">@lang('staffcompliance::compliance.document_types')</h4>
            @if(user()->permission('manage_compliance_document_types') != 'none')
                <button class="btn btn-primary btn-sm" id="add-document-type-btn">
                    <i class="fa fa-plus mr-1"></i>@lang('staffcompliance::compliance.add_document_type')
                </button>
            @endif
        </div>

        <div class="d-flex flex-column w-tables rounded bg-white">
            <x-datatable.table class="border-0">
                <x-slot name="thead">
                    <tr>
                        <th>#</th>
                        <th>@lang('staffcompliance::compliance.document_type')</th>
                        <th>Code</th>
                        <th>@lang('staffcompliance::compliance.is_mandatory')</th>
                        <th>@lang('staffcompliance::compliance.renewal_period_months')</th>
                        <th>@lang('staffcompliance::compliance.vertical')</th>
                        <th class="text-right">@lang('app.action')</th>
                    </tr>
                </x-slot>
                <x-slot name="tbody">
                    @forelse ($types as $type)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $type->name }}</td>
                            <td><code>{{ $type->code }}</code></td>
                            <td>
                                @if($type->is_mandatory)
                                    <span class="badge badge-success">@lang('staffcompliance::compliance.mandatory')</span>
                                @else
                                    <span class="badge badge-secondary">@lang('staffcompliance::compliance.optional')</span>
                                @endif
                            </td>
                            <td>{{ $type->renewal_period_months ? $type->renewal_period_months . ' months' : '—' }}</td>
                            <td>
                                @if($type->vertical)
                                    @foreach($type->vertical as $v)
                                        <span class="badge badge-light border">{{ $v }}</span>
                                    @endforeach
                                @else
                                    <span class="text-muted">All</span>
                                @endif
                            </td>
                            <td class="text-right">
                                @if(user()->permission('manage_compliance_document_types') != 'none')
                                    <button class="btn btn-sm btn-outline-secondary edit-type-btn"
                                            data-id="{{ $type->id }}"
                                            data-name="{{ $type->name }}"
                                            data-mandatory="{{ $type->is_mandatory ? '1' : '0' }}"
                                            data-renewal="{{ $type->renewal_period_months }}"
                                            data-description="{{ $type->description }}"
                                            data-url="{{ route('compliance.types.update', $type->id) }}">
                                        <i class="fa fa-edit"></i>
                                    </button>
                                    <button class="btn btn-sm btn-outline-danger delete-type-btn"
                                            data-url="{{ route('compliance.types.destroy', $type->id) }}">
                                        <i class="fa fa-trash"></i>
                                    </button>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center text-muted py-4">
                                @lang('staffcompliance::compliance.no_document_types')
                            </td>
                        </tr>
                    @endforelse
                </x-slot>
            </x-datatable.table>
        </div>
    </div>

    {{-- Add / Edit Modal --}}
    <div class="modal fade" id="documentTypeModal" tabindex="-1" role="dialog">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="documentTypeModalTitle">@lang('staffcompliance::compliance.add_document_type')</h5>
                    <button type="button" class="close" data-dismiss="modal">
                        <span>&times;</span>
                    </button>
                </div>
                <form id="documentTypeForm">
                    @csrf
                    <input type="hidden" id="form-method" value="POST">
                    <input type="hidden" id="form-url" value="{{ route('compliance.types.store') }}">
                    <div class="modal-body">
                        <div class="form-group">
                            <label>@lang('staffcompliance::compliance.document_type') <span class="text-danger">*</span></label>
                            <input type="text" name="name" id="type-name" class="form-control" required>
                        </div>
                        <div class="form-group" id="code-group">
                            <label>Code <span class="text-danger">*</span></label>
                            <input type="text" name="code" id="type-code" class="form-control" placeholder="e.g. police_check">
                        </div>
                        <div class="form-group">
                            <label>@lang('staffcompliance::compliance.renewal_period_months')</label>
                            <input type="number" name="renewal_period_months" id="type-renewal" class="form-control" min="1">
                        </div>
                        <div class="form-group">
                            <div class="custom-control custom-checkbox">
                                <input type="checkbox" class="custom-control-input" name="is_mandatory" id="type-mandatory" value="1">
                                <label class="custom-control-label" for="type-mandatory">@lang('staffcompliance::compliance.is_mandatory')</label>
                            </div>
                        </div>
                        <div class="form-group">
                            <label>@lang('staffcompliance::compliance.description')</label>
                            <textarea name="description" id="type-description" class="form-control" rows="3"></textarea>
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
    $('#add-document-type-btn').on('click', function () {
        $('#documentTypeModalTitle').text('@lang('staffcompliance::compliance.add_document_type')');
        $('#documentTypeForm')[0].reset();
        $('#form-method').val('POST');
        $('#form-url').val('{{ route('compliance.types.store') }}');
        $('#code-group').show();
        $('#documentTypeModal').modal('show');
    });

    $(document).on('click', '.edit-type-btn', function () {
        const btn = $(this);
        $('#documentTypeModalTitle').text('@lang('staffcompliance::compliance.edit_document_type')');
        $('#type-name').val(btn.data('name'));
        $('#type-renewal').val(btn.data('renewal'));
        $('#type-description').val(btn.data('description'));
        $('#type-mandatory').prop('checked', btn.data('mandatory') == '1');
        $('#form-method').val('PUT');
        $('#form-url').val(btn.data('url'));
        $('#code-group').hide();
        $('#documentTypeModal').modal('show');
    });

    $('#documentTypeForm').on('submit', function (e) {
        e.preventDefault();
        const method = $('#form-method').val();
        const url    = $('#form-url').val();
        const data   = $(this).serializeArray();

        // Append method spoofing for PUT
        if (method === 'PUT') {
            data.push({ name: '_method', value: 'PUT' });
        }

        $.post(url, data)
            .done(function () {
                $('#documentTypeModal').modal('hide');
                location.reload();
            })
            .fail(function (xhr) {
                alert(xhr.responseJSON?.message || 'An error occurred.');
            });
    });

    $(document).on('click', '.delete-type-btn', function () {
        if (!confirm('@lang('messages.deleteConfirmation')')) return;
        const url = $(this).data('url');
        $.ajax({ url: url, type: 'DELETE', data: { _token: '{{ csrf_token() }}' } })
            .done(function () { location.reload(); })
            .fail(function (xhr) { alert(xhr.responseJSON?.message || 'Delete failed.'); });
    });
</script>
@endpush
