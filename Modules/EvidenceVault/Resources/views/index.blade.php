@extends('layouts.app')

@push('datatable-styles')
    @include('sections.datatable_css')
@endpush

@section('filter-section')
    <x-filters.filter-box>
        {{-- Job Reference --}}
        <div class="select-box d-flex pr-2 border-right-grey">
            <p class="mb-0 pr-2 f-14 text-dark-grey d-flex align-items-center">@lang('app.job')</p>
            <input type="text"
                   class="form-control f-14 border-additional-grey"
                   id="filter-job-reference"
                   placeholder="Job reference…"
                   value="{{ request('job_reference') }}">
        </div>

        {{-- Date range --}}
        <div class="select-box d-flex pr-2 border-right-grey">
            <p class="mb-0 pr-2 f-14 text-dark-grey d-flex align-items-center">@lang('app.duration')</p>
            <input type="text"
                   class="form-control f-14 border-additional-grey"
                   id="datatableRange"
                   placeholder="@lang('placeholders.dateRange')">
        </div>

        {{-- Reset --}}
        <div class="select-box d-flex py-1 px-lg-2 px-0">
            <x-forms.button-secondary class="btn-xs d-none" id="reset-filters" icon="times-circle">
                @lang('app.clearFilters')
            </x-forms.button-secondary>
        </div>
    </x-filters.filter-box>
@endsection

@section('content')
    <div class="content-wrapper">
        <div class="d-grid d-lg-flex justify-content-lg-between align-items-center action-bar">
            <div id="table-actions" class="flex-grow-1 align-items-center">
                <h4 class="f-21 font-weight-normal text-capitalize mb-0 mr-2">
                    Evidence Vault
                </h4>
            </div>
        </div>

        <div class="d-flex flex-wrap w-100 mt-2">
            <div id="evidence-vault-table-div" class="w-100">
                <x-table>
                    <x-slot name="thead">
                        <th>#</th>
                        <th>Job Reference</th>
                        <th>Submitted By</th>
                        <th>Photos</th>
                        <th>Signed?</th>
                        <th>Date</th>
                        <th class="text-right">@lang('app.action')</th>
                    </x-slot>

                    @forelse($submissions as $submission)
                        <tr>
                            <td>{{ $loop->iteration + ($submissions->currentPage() - 1) * $submissions->perPage() }}</td>
                            <td>
                                @if($submission->job_reference)
                                    <span class="badge badge-secondary">{{ $submission->job_reference }}</span>
                                @elseif($submission->job_id)
                                    Job #{{ $submission->job_id }}
                                @else
                                    <span class="text-muted">—</span>
                                @endif
                            </td>
                            <td>
                                @if($submission->submitter)
                                    {{ $submission->submitter->name }}
                                @else
                                    <span class="text-muted">Unknown</span>
                                @endif
                            </td>
                            <td>
                                <span class="badge badge-info">{{ $submission->photos->count() }} photo(s)</span>
                            </td>
                            <td>
                                @if($submission->client_signed)
                                    <span class="badge badge-success"><i class="fa fa-check"></i> Signed</span>
                                @elseif($submission->photos->where('is_site_locked_photo', true)->count())
                                    <span class="badge badge-warning">Site Photo</span>
                                @else
                                    <span class="badge badge-secondary">No signature</span>
                                @endif
                            </td>
                            <td>{{ $submission->created_at->format('d M Y H:i') }}</td>
                            <td class="text-right">
                                <a href="{{ route('evidence-vault.show', $submission->id) }}"
                                   class="btn btn-sm btn-outline-primary">
                                    <i class="fa fa-eye"></i> View
                                </a>
                                <x-forms.button-secondary
                                    class="btn-xs delete-confirm"
                                    data-row-id="{{ $submission->id }}"
                                    data-confirm-url="{{ route('evidence-vault.destroy', $submission->id) }}"
                                    icon="trash">
                                </x-forms.button-secondary>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center text-muted py-4">
                                No evidence submissions found.
                            </td>
                        </tr>
                    @endforelse
                </x-table>

                <div class="mt-3">
                    {{ $submissions->links() }}
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
<script>
    // Simple filter wiring – re-submit the page with query params.
    $('#filter-job-reference').on('keyup', function () {
        clearTimeout(window._evSearchTimer);
        const val = $(this).val();
        window._evSearchTimer = setTimeout(function () {
            const url = new URL(window.location.href);
            url.searchParams.set('job_reference', val);
            window.location = url.toString();
        }, 600);
    });

    $('#reset-filters').on('click', function () {
        window.location = '{{ route('evidence-vault.index') }}';
    });

    $('#filter-job-reference').on('input', function () {
        if ($(this).val().length > 0) {
            $('#reset-filters').removeClass('d-none');
        } else {
            $('#reset-filters').addClass('d-none');
        }
    });
</script>
@endpush
