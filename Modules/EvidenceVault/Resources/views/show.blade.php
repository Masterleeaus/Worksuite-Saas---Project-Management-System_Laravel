@extends('layouts.app')

@section('content')
    <div class="content-wrapper">

        {{-- Breadcrumb --}}
        <div class="d-flex align-items-center mb-4">
            <a href="{{ route('evidence-vault.index') }}" class="text-dark-grey mr-2">
                <i class="fa fa-arrow-left"></i> Evidence Vault
            </a>
            <span class="text-dark-grey">/</span>
            <span class="ml-2">
                {{ $submission->job_reference ?: 'Submission #' . $submission->id }}
            </span>
        </div>

        <div class="row">

            {{-- Left column: metadata --}}
            <div class="col-lg-4 mb-4">
                <div class="card h-100">
                    <div class="card-header f-15 f-w-500 bg-white">
                        Submission Details
                    </div>
                    <div class="card-body">
                        <table class="table table-borderless table-sm">
                            <tr>
                                <td class="text-dark-grey f-13">Submission ID</td>
                                <td class="f-14">#{{ $submission->id }}</td>
                            </tr>
                            <tr>
                                <td class="text-dark-grey f-13">Job Reference</td>
                                <td class="f-14">
                                    {{ $submission->job_reference ?: ($submission->job_id ? 'Job #' . $submission->job_id : '—') }}
                                </td>
                            </tr>
                            <tr>
                                <td class="text-dark-grey f-13">Submitted By</td>
                                <td class="f-14">
                                    {{ optional($submission->submitter)->name ?? 'Unknown' }}
                                </td>
                            </tr>
                            <tr>
                                <td class="text-dark-grey f-13">Date</td>
                                <td class="f-14">{{ $submission->created_at->format('d M Y H:i') }}</td>
                            </tr>
                            <tr>
                                <td class="text-dark-grey f-13">Photos</td>
                                <td class="f-14">{{ $submission->photos->count() }}</td>
                            </tr>
                            <tr>
                                <td class="text-dark-grey f-13">Client Signed</td>
                                <td class="f-14">
                                    @if($submission->client_signed)
                                        <span class="badge badge-success">Yes</span>
                                    @else
                                        <span class="badge badge-secondary">No</span>
                                    @endif
                                </td>
                            </tr>
                        </table>

                        @if($submission->notes)
                            <hr>
                            <p class="f-13 text-dark-grey mb-1">Notes</p>
                            <p class="f-14">{{ $submission->notes }}</p>
                        @endif
                    </div>
                </div>
            </div>

            {{-- Right column: photos + signature --}}
            <div class="col-lg-8 mb-4">

                {{-- Photos --}}
                <div class="card mb-4">
                    <div class="card-header f-15 f-w-500 bg-white d-flex justify-content-between align-items-center">
                        <span>Photos ({{ $submission->photos->count() }})</span>
                    </div>
                    <div class="card-body">
                        @if($submission->photos->isEmpty())
                            <p class="text-muted text-center py-3">No photos attached to this submission.</p>
                        @else
                            <div class="row">
                                @foreach($submission->photos as $photo)
                                    <div class="col-6 col-md-4 mb-3">
                                        <div class="position-relative">
                                            <a href="{{ $photo->photo_url }}" target="_blank" rel="noopener">
                                                <img src="{{ $photo->photo_url }}"
                                                     alt="{{ $photo->original_filename }}"
                                                     class="img-fluid rounded shadow-sm"
                                                     style="object-fit:cover; height:160px; width:100%;">
                                            </a>
                                            @if($photo->is_site_locked_photo)
                                                <span class="badge badge-warning position-absolute"
                                                      style="top:6px;left:6px;">
                                                    Locked Site
                                                </span>
                                            @endif
                                        </div>
                                        <p class="f-11 text-muted mt-1 mb-0 text-truncate"
                                           title="{{ $photo->original_filename }}">
                                            {{ $photo->original_filename }}
                                        </p>
                                        @if($photo->file_size)
                                            <p class="f-11 text-muted mb-0">
                                                {{ number_format($photo->file_size / 1024, 1) }} KB
                                            </p>
                                        @endif
                                    </div>
                                @endforeach
                            </div>
                        @endif
                    </div>
                </div>

                {{-- Digital Signature --}}
                @if($submission->signature_data)
                    <div class="card">
                        <div class="card-header f-15 f-w-500 bg-white">
                            Client Signature
                        </div>
                        <div class="card-body text-center">
                            <img src="{{ $submission->signature_data }}"
                                 alt="Client Signature"
                                 class="img-fluid border rounded"
                                 style="max-height:200px; background:#fff;">
                        </div>
                    </div>
                @endif

            </div>
        </div>

        {{-- Delete button --}}
        <div class="mt-2">
            <x-forms.button-secondary
                class="btn-sm delete-confirm"
                data-row-id="{{ $submission->id }}"
                data-confirm-url="{{ route('evidence-vault.destroy', $submission->id) }}"
                icon="trash">
                Delete Submission
            </x-forms.button-secondary>
        </div>

    </div>
@endsection
