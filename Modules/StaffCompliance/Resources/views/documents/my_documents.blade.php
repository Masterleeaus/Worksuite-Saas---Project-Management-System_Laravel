@extends('layouts.app')

@section('content')
    <div class="content-wrapper">
        <div class="d-flex justify-content-between align-items-center action-bar mb-3">
            <h4 class="mb-0">@lang('staffcompliance::compliance.my_documents')</h4>
            <button class="btn btn-primary btn-sm" id="upload-my-doc-btn">
                <i class="fa fa-upload mr-1"></i>@lang('staffcompliance::compliance.upload_document')
            </button>
        </div>

        <div class="d-flex flex-column w-tables rounded bg-white">
            <x-datatable.table class="border-0">
                <x-slot name="thead">
                    <tr>
                        <th>#</th>
                        <th>@lang('staffcompliance::compliance.document_type')</th>
                        <th>@lang('staffcompliance::compliance.document_number')</th>
                        <th>@lang('staffcompliance::compliance.issue_date')</th>
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
                            <td>{{ $doc->documentType?->name ?? '—' }}</td>
                            <td>{{ $doc->document_number ?? '—' }}</td>
                            <td>{{ $doc->issue_date?->format(company()->date_format ?? 'Y-m-d') }}</td>
                            <td>
                                @if($doc->expiry_date)
                                    @php $isExpired = $doc->expiry_date->isPast(); @endphp
                                    <span class="{{ $isExpired ? 'text-danger font-weight-bold' : '' }}">
                                        {{ $doc->expiry_date->format(company()->date_format ?? 'Y-m-d') }}
                                    </span>
                                @else
                                    <span class="text-muted">—</span>
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
        </div>
    </div>

    {{-- Upload Modal --}}
    <div class="modal fade" id="uploadMyDocModal" tabindex="-1" role="dialog">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">@lang('staffcompliance::compliance.upload_document')</h5>
                    <button type="button" class="close" data-dismiss="modal"><span>&times;</span></button>
                </div>
                <form id="uploadMyDocForm" enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" name="user_id" value="{{ user()->id }}">
                    <div class="modal-body">
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
@endsection

@push('scripts')
<script>
    $('#upload-my-doc-btn').on('click', function () {
        $('#uploadMyDocForm')[0].reset();
        $('#uploadMyDocModal').modal('show');
    });

    $('#uploadMyDocForm').on('submit', function (e) {
        e.preventDefault();
        const formData = new FormData(this);
        $.ajax({
            url: '{{ route('compliance.documents.store') }}',
            type: 'POST',
            data: formData,
            processData: false,
            contentType: false,
        }).done(function () {
            $('#uploadMyDocModal').modal('hide');
            location.reload();
        }).fail(function (xhr) {
            alert(xhr.responseJSON?.message || 'Upload failed.');
        });
    });
</script>
@endpush
