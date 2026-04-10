@extends('layouts.app')

@section('content')
<div class="container py-4" style="max-width:680px;">

    @if($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach($errors->all() as $e)
                    <li>{{ $e }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="card shadow-sm">
        <div class="card-header">
            <h4 class="mb-0"><i class="ti-plus me-2"></i>Request Extras for Your Next Clean</h4>
        </div>
        <div class="card-body">

            {{-- Next job preview --}}
            @if($nextJob)
                <div class="alert alert-info mb-4">
                    <strong><i class="ti-calendar me-1"></i>Your next scheduled clean:</strong>
                    {{ $nextJob->name }}
                    @if($nextJob->scheduled_date_start)
                        on {{ $nextJob->scheduled_date_start->format('d M Y \a\t H:i') }}
                    @endif
                    @if($nextJob->person)
                        with {{ $nextJob->person->name }}
                    @endif
                    — extras will be attached to this job.
                </div>
            @else
                <div class="alert alert-warning mb-4">
                    <i class="ti-alert-circle me-1"></i>
                    No upcoming job found. Your extras request will still be saved and our team will be in touch.
                </div>
            @endif

            <form method="POST" action="{{ route('clientpulse.portal.extras.store') }}">
                @csrf

                @if($nextJob)
                    <input type="hidden" name="job_id" value="{{ $nextJob->id }}">
                @endif

                {{-- Extras checklist --}}
                @if($items->isNotEmpty())
                    <div class="mb-4">
                        <label class="form-label fw-semibold">Select extras</label>
                        <div class="row g-2">
                            @foreach($items as $item)
                                <div class="col-md-6">
                                    <div class="form-check border rounded p-3 h-100">
                                        <input class="form-check-input" type="checkbox"
                                               name="items[]" value="{{ $item->id }}"
                                               id="extra_{{ $item->id }}"
                                               {{ in_array($item->id, old('items', [])) ? 'checked' : '' }}>
                                        <label class="form-check-label w-100" for="extra_{{ $item->id }}">
                                            <strong>{{ $item->name }}</strong>
                                            @if($item->description)
                                                <small class="text-muted d-block">{{ $item->description }}</small>
                                            @endif
                                        </label>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif

                {{-- Custom note --}}
                <div class="mb-4">
                    <label class="form-label fw-semibold" for="custom_note">
                        Anything else? <span class="text-muted fw-normal">(optional)</span>
                    </label>
                    <textarea id="custom_note" name="custom_note"
                              class="form-control @error('custom_note') is-invalid @enderror"
                              rows="3"
                              placeholder="E.g. please focus on the master bathroom…">{{ old('custom_note') }}</textarea>
                    @error('custom_note')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="d-flex gap-2">
                    <button type="submit" class="btn btn-primary px-4">
                        <i class="ti-check me-1"></i>Submit Request
                    </button>
                    <a href="{{ route('clientpulse.portal.history.index') }}" class="btn btn-outline-secondary">
                        Cancel
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
