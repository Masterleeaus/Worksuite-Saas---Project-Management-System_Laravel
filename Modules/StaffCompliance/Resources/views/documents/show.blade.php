@extends('layouts.app')

@section('content')
    <div class="content-wrapper">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h4 class="mb-0">@lang('staffcompliance::compliance.compliance_docs') — #{{ $document->id }}</h4>
            <a href="{{ route('compliance.documents.index') }}" class="btn btn-outline-secondary btn-sm">
                <i class="fa fa-arrow-left mr-1"></i>@lang('app.back')
            </a>
        </div>

        <div class="row">
            <div class="col-md-8">
                <div class="card shadow-sm mb-4">
                    <div class="card-body">
                        <table class="table table-borderless">
                            <tbody>
                                <tr>
                                    <th width="35%">@lang('staffcompliance::compliance.worker')</th>
                                    <td>{{ $document->worker?->name ?? '—' }}</td>
                                </tr>
                                <tr>
                                    <th>@lang('staffcompliance::compliance.document_type')</th>
                                    <td>{{ $document->documentType?->name ?? '—' }}</td>
                                </tr>
                                <tr>
                                    <th>@lang('staffcompliance::compliance.document_number')</th>
                                    <td>{{ $document->document_number ?? '—' }}</td>
                                </tr>
                                <tr>
                                    <th>@lang('staffcompliance::compliance.issuing_authority')</th>
                                    <td>{{ $document->issuing_authority ?? '—' }}</td>
                                </tr>
                                <tr>
                                    <th>@lang('staffcompliance::compliance.issue_date')</th>
                                    <td>{{ $document->issue_date?->format(company()->date_format ?? 'Y-m-d') }}</td>
                                </tr>
                                <tr>
                                    <th>@lang('staffcompliance::compliance.expiry_date')</th>
                                    <td>
                                        @if($document->expiry_date)
                                            @php $expired = $document->expiry_date->isPast(); @endphp
                                            <span class="{{ $expired ? 'text-danger font-weight-bold' : '' }}">
                                                {{ $document->expiry_date->format(company()->date_format ?? 'Y-m-d') }}
                                            </span>
                                            @if($expired)
                                                <span class="badge badge-danger ml-1">@lang('staffcompliance::compliance.expired')</span>
                                            @endif
                                        @else
                                            <span class="text-muted">No expiry</span>
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <th>@lang('app.status')</th>
                                    <td>
                                        @php
                                            $badgeClass = match($document->status) {
                                                'verified'       => 'badge-success',
                                                'rejected'       => 'badge-danger',
                                                'expired'        => 'badge-warning',
                                                default          => 'badge-secondary',
                                            };
                                        @endphp
                                        <span class="badge {{ $badgeClass }}">
                                            {{ ucfirst(str_replace('_', ' ', $document->status)) }}
                                        </span>
                                    </td>
                                </tr>
                                @if($document->verified_by)
                                    <tr>
                                        <th>@lang('staffcompliance::compliance.verified_by')</th>
                                        <td>{{ $document->verifier?->name ?? '—' }}</td>
                                    </tr>
                                    <tr>
                                        <th>@lang('staffcompliance::compliance.verified_at')</th>
                                        <td>{{ $document->verified_at?->format(company()->date_format ?? 'Y-m-d') }}</td>
                                    </tr>
                                @endif
                                @if($document->rejection_reason)
                                    <tr>
                                        <th>@lang('staffcompliance::compliance.rejection_reason')</th>
                                        <td class="text-danger">{{ $document->rejection_reason }}</td>
                                    </tr>
                                @endif
                                @if($document->notes)
                                    <tr>
                                        <th>@lang('staffcompliance::compliance.notes')</th>
                                        <td>{{ $document->notes }}</td>
                                    </tr>
                                @endif
                            </tbody>
                        </table>
                    </div>
                </div>

                @if($document->file_path)
                    <div class="card shadow-sm mb-4">
                        <div class="card-header">@lang('staffcompliance::compliance.file')</div>
                        <div class="card-body">
                            <a href="{{ asset('storage/' . $document->file_path) }}"
                               target="_blank" class="btn btn-outline-primary btn-sm">
                                <i class="fa fa-download mr-1"></i>@lang('staffcompliance::compliance.download')
                            </a>
                        </div>
                    </div>
                @endif
            </div>

            <div class="col-md-4">
                @if(user()->permission('verify_compliance_documents') != 'none' && $document->status === 'pending_review')
                    <div class="card shadow-sm mb-4">
                        <div class="card-header">@lang('app.actions')</div>
                        <div class="card-body">
                            <button class="btn btn-success btn-sm w-100 mb-2" id="verify-btn"
                                    data-url="{{ route('compliance.documents.verify', $document->id) }}">
                                <i class="fa fa-check mr-1"></i>@lang('staffcompliance::compliance.verified')
                            </button>
                            <button class="btn btn-danger btn-sm w-100" id="reject-btn">
                                <i class="fa fa-times mr-1"></i>@lang('staffcompliance::compliance.rejected')
                            </button>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>

    {{-- Reject Modal --}}
    <div class="modal fade" id="rejectModal" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">@lang('staffcompliance::compliance.rejection_reason')</h5>
                    <button type="button" class="close" data-dismiss="modal"><span>&times;</span></button>
                </div>
                <form id="rejectForm">
                    @csrf
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
@endsection

@push('scripts')
<script>
    $('#verify-btn').on('click', function () {
        $.post($(this).data('url'), { _token: '{{ csrf_token() }}' })
            .done(function () { location.reload(); })
            .fail(function (xhr) { alert(xhr.responseJSON?.message || 'Error.'); });
    });

    $('#reject-btn').on('click', function () {
        $('#rejectForm')[0].reset();
        $('#rejectModal').modal('show');
    });

    $('#rejectForm').on('submit', function (e) {
        e.preventDefault();
        $.post('{{ route('compliance.documents.reject', $document->id) }}', $(this).serializeArray())
            .done(function () {
                $('#rejectModal').modal('hide');
                location.reload();
            })
            .fail(function (xhr) { alert(xhr.responseJSON?.message || 'Error.'); });
    });
</script>
@endpush
