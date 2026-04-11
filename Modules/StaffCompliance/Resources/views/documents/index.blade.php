@extends('layouts.app')

@section('filter-section')
    <x-filters.filter-box>
        {{-- Status Filter --}}
        <div class="select-box d-flex py-2 px-lg-2 px-md-2 px-0 border-right-grey border-right-grey-sm-0">
            <p class="mb-0 pr-2 f-14 text-dark-grey d-flex align-items-center">@lang('app.status')</p>
            <div class="select-status">
                <select class="form-control select-picker" name="status" id="doc-status">
                    <option value="">@lang('app.all')</option>
                    <option value="pending_review" {{ request('status') == 'pending_review' ? 'selected' : '' }}>@lang('staffcompliance::compliance.pending_review')</option>
                    <option value="verified" {{ request('status') == 'verified' ? 'selected' : '' }}>@lang('staffcompliance::compliance.verified')</option>
                    <option value="expired" {{ request('status') == 'expired' ? 'selected' : '' }}>@lang('staffcompliance::compliance.expired')</option>
                    <option value="rejected" {{ request('status') == 'rejected' ? 'selected' : '' }}>@lang('staffcompliance::compliance.rejected')</option>
                </select>
            </div>
        </div>

        {{-- Document Type Filter --}}
        <div class="select-box d-flex py-2 px-lg-2 px-md-2 px-0 border-right-grey border-right-grey-sm-0">
            <p class="mb-0 pr-2 f-14 text-dark-grey d-flex align-items-center">@lang('staffcompliance::compliance.document_type')</p>
            <div class="select-status">
                <select class="form-control select-picker" name="document_type_id" id="doc-type-filter">
                    <option value="">@lang('app.all')</option>
                    @foreach($documentTypes as $type)
                        <option value="{{ $type->id }}" {{ request('document_type_id') == $type->id ? 'selected' : '' }}>{{ $type->name }}</option>
                    @endforeach
                </select>
            </div>
        </div>

        {{-- Worker Filter --}}
        @if(user()->permission('manage_compliance') != 'none')
            <div class="select-box d-flex py-2 px-lg-2 px-md-2 px-0 border-right-grey border-right-grey-sm-0">
                <p class="mb-0 pr-2 f-14 text-dark-grey d-flex align-items-center">@lang('staffcompliance::compliance.worker')</p>
                <div class="select-status">
                    <select class="form-control select-picker" name="worker_id" id="doc-worker-filter">
                        <option value="">@lang('app.all')</option>
                        @foreach($workers as $worker)
                            <option value="{{ $worker->id }}" {{ request('worker_id') == $worker->id ? 'selected' : '' }}>{{ $worker->name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
        @endif

        {{-- Reset --}}
        <div class="select-box d-flex py-1 px-lg-2 px-md-2 px-0">
            <x-forms.button-secondary class="btn-xs d-none" id="reset-filters" icon="times-circle">
                @lang('app.clearFilters')
            </x-forms.button-secondary>
        </div>
    </x-filters.filter-box>
@endsection

@section('content')
    <div class="content-wrapper">
        <div class="d-flex justify-content-between align-items-center action-bar mb-3">
            <h4 class="mb-0">@lang('staffcompliance::compliance.compliance_docs')</h4>
            @if(user()->permission('manage_compliance') != 'none')
                <button class="btn btn-primary btn-sm" id="add-document-btn">
                    <i class="fa fa-upload mr-1"></i>@lang('staffcompliance::compliance.upload_document')
                </button>
            @endif
        </div>

        <div class="d-flex flex-column w-tables rounded bg-white">
            <x-datatable.table class="border-0">
                <x-slot name="thead">
                    <tr>
                        <th>#</th>
                        <th>@lang('staffcompliance::compliance.worker')</th>
                        <th>@lang('staffcompliance::compliance.document_type')</th>
                        <th>@lang('staffcompliance::compliance.document_number')</th>
                        <th>@lang('staffcompliance::compliance.expiry_date')</th>
                        <th>@lang('app.status')</th>
                        <th class="text-right">@lang('app.action')</th>
                    </tr>
                </x-slot>
                <x-slot name="tbody">
                    @forelse ($documents as $doc)
                        @php
                            $badgeClass = match($doc->status) {
                                'verified'       => 'badge-success',
                                'rejected'       => 'badge-danger',
                                'expired'        => 'badge-warning',
                                default          => 'badge-secondary',
                            };
                        @endphp
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $doc->worker?->name ?? '—' }}</td>
                            <td>{{ $doc->documentType?->name ?? '—' }}</td>
                            <td>{{ $doc->document_number ?? '—' }}</td>
                            <td>
                                @if($doc->expiry_date)
                                    @php
                                        $isExpired  = $doc->expiry_date->isPast();
                                        $isExpiring = !$isExpired && $doc->expiry_date->diffInDays(now()) <= 30 && $doc->expiry_date->isFuture();
                                    @endphp
                                    <span class="{{ $isExpired ? 'text-danger font-weight-bold' : ($isExpiring ? 'text-warning font-weight-bold' : '') }}">
                                        {{ $doc->expiry_date->format(company()->date_format ?? 'Y-m-d') }}
                                    </span>
                                @else
                                    <span class="text-muted">@lang('app.no_expiry')</span>
                                @endif
                            </td>
                            <td>
                                <span class="badge {{ $badgeClass }}">
                                    {{ ucfirst(str_replace('_', ' ', $doc->status)) }}
                                </span>
                            </td>
                            <td class="text-right">
                                <a href="{{ route('compliance.documents.show', $doc->id) }}"
                                   class="btn btn-sm btn-outline-primary">
                                    <i class="fa fa-eye"></i>
                                </a>
                                @if(user()->permission('verify_compliance_documents') != 'none' && $doc->status === 'pending_review')
                                    <button class="btn btn-sm btn-outline-success verify-doc-btn"
                                            data-url="{{ route('compliance.documents.verify', $doc->id) }}">
                                        <i class="fa fa-check"></i>
                                    </button>
                                    <button class="btn btn-sm btn-outline-danger reject-doc-btn"
                                            data-id="{{ $doc->id }}"
                                            data-url="{{ route('compliance.documents.reject', $doc->id) }}">
                                        <i class="fa fa-times"></i>
                                    </button>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center text-muted py-4">
                                @lang('staffcompliance::compliance.no_documents')
                            </td>
                        </tr>
                    @endforelse
                </x-slot>
            </x-datatable.table>

            <div class="d-flex justify-content-between align-items-center px-3 pb-3">
                <div>
                    @lang('app.showing') {{ $documents->firstItem() ?? 0 }}–{{ $documents->lastItem() ?? 0 }}
                    @lang('app.of') {{ $documents->total() }}
                </div>
                {{ $documents->withQueryString()->links() }}
            </div>
        </div>
    </div>

    {{-- Upload Document Modal --}}
    @if(user()->permission('manage_compliance') != 'none')
        <div class="modal fade" id="uploadDocumentModal" tabindex="-1" role="dialog">
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">@lang('staffcompliance::compliance.upload_document')</h5>
                        <button type="button" class="close" data-dismiss="modal"><span>&times;</span></button>
                    </div>
                    <form id="uploadDocumentForm" enctype="multipart/form-data">
                        @csrf
                        <div class="modal-body">
                            <div class="form-group">
                                <label>@lang('staffcompliance::compliance.worker') <span class="text-danger">*</span></label>
                                <select name="user_id" class="form-control select-picker" required>
                                    <option value="">-- Select Worker --</option>
                                    @foreach($workers as $worker)
                                        <option value="{{ $worker->id }}">{{ $worker->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group">
                                <label>@lang('staffcompliance::compliance.document_type') <span class="text-danger">*</span></label>
                                <select name="document_type_id" class="form-control select-picker" required>
                                    <option value="">-- Select Type --</option>
                                    @foreach($documentTypes as $type)
                                        <option value="{{ $type->id }}">{{ $type->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group">
                                <label>@lang('staffcompliance::compliance.document_number')</label>
                                <input type="text" name="document_number" class="form-control">
                            </div>
                            <div class="form-group">
                                <label>@lang('staffcompliance::compliance.issuing_authority')</label>
                                <input type="text" name="issuing_authority" class="form-control">
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>@lang('staffcompliance::compliance.issue_date') <span class="text-danger">*</span></label>
                                        <input type="date" name="issue_date" class="form-control" required>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>@lang('staffcompliance::compliance.expiry_date')</label>
                                        <input type="date" name="expiry_date" class="form-control">
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <label>@lang('staffcompliance::compliance.file')</label>
                                <input type="file" name="file" class="form-control-file" accept=".pdf,.jpg,.jpeg,.png">
                            </div>
                            <div class="form-group">
                                <label>@lang('staffcompliance::compliance.notes')</label>
                                <textarea name="notes" class="form-control" rows="3"></textarea>
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

        {{-- Reject Reason Modal --}}
        <div class="modal fade" id="rejectDocumentModal" tabindex="-1" role="dialog">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">@lang('staffcompliance::compliance.rejection_reason')</h5>
                        <button type="button" class="close" data-dismiss="modal"><span>&times;</span></button>
                    </div>
                    <form id="rejectDocumentForm">
                        @csrf
                        <input type="hidden" id="reject-doc-url" value="">
                        <div class="modal-body">
                            <div class="form-group">
                                <label>@lang('staffcompliance::compliance.rejection_reason') <span class="text-danger">*</span></label>
                                <textarea name="rejection_reason" class="form-control" rows="4" required></textarea>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">@lang('app.cancel')</button>
                            <button type="submit" class="btn btn-danger">@lang('app.reject')</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @endif
@endsection

@push('scripts')
<script>
    // Filters
    $('#doc-status, #doc-type-filter, #doc-worker-filter').on('change', function () {
        const params = new URLSearchParams();
        const status = $('#doc-status').val();
        const typeId = $('#doc-type-filter').val();
        const workerId = $('#doc-worker-filter').val();
        if (status)   params.set('status', status);
        if (typeId)   params.set('document_type_id', typeId);
        if (workerId) params.set('worker_id', workerId);
        const qs = params.toString();
        window.location.href = "{{ route('compliance.documents.index') }}" + (qs ? '?' + qs : '');
    });

    $('#reset-filters').on('click', function () {
        window.location.href = "{{ route('compliance.documents.index') }}";
    });

    // Upload
    $('#add-document-btn').on('click', function () {
        $('#uploadDocumentForm')[0].reset();
        $('#uploadDocumentModal').modal('show');
    });

    $('#uploadDocumentForm').on('submit', function (e) {
        e.preventDefault();
        const formData = new FormData(this);
        $.ajax({
            url: '{{ route('compliance.documents.store') }}',
            type: 'POST',
            data: formData,
            processData: false,
            contentType: false,
        }).done(function () {
            $('#uploadDocumentModal').modal('hide');
            location.reload();
        }).fail(function (xhr) {
            alert(xhr.responseJSON?.message || 'Upload failed.');
        });
    });

    // Verify
    $(document).on('click', '.verify-doc-btn', function () {
        const url = $(this).data('url');
        $.post(url, { _token: '{{ csrf_token() }}' })
            .done(function () { location.reload(); })
            .fail(function (xhr) { alert(xhr.responseJSON?.message || 'Error.'); });
    });

    // Reject
    $(document).on('click', '.reject-doc-btn', function () {
        $('#reject-doc-url').val($(this).data('url'));
        $('#rejectDocumentForm')[0].reset();
        $('#rejectDocumentModal').modal('show');
    });

    $('#rejectDocumentForm').on('submit', function (e) {
        e.preventDefault();
        const url  = $('#reject-doc-url').val();
        const data = $(this).serializeArray();
        $.post(url, data)
            .done(function () {
                $('#rejectDocumentModal').modal('hide');
                location.reload();
            })
            .fail(function (xhr) { alert(xhr.responseJSON?.message || 'Error.'); });
    });
</script>
@endpush
