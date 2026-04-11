@extends('layouts.app')

@section('content')
    <div class="content-wrapper">
        <div class="d-flex justify-content-between align-items-center action-bar mb-4">
            <h4 class="mb-0">Approve In/Out Permit — #{{ $permit->id }}</h4>
            <a href="{{ route('security.inout-permits.show', $permit->id) }}" class="btn btn-secondary btn-sm">
                <i class="fa fa-arrow-left mr-1"></i> @lang('app.back')
            </a>
        </div>

        <div class="card shadow-sm">
            <div class="card-body">
                <dl class="row mb-4">
                    <dt class="col-sm-3">Visitor Name</dt><dd class="col-sm-9">{{ $permit->visitor_name }}</dd>
                    <dt class="col-sm-3">Purpose</dt><dd class="col-sm-9">{{ $permit->purpose }}</dd>
                    <dt class="col-sm-3">Entry Time</dt><dd class="col-sm-9">{{ $permit->entry_time?->format('Y-m-d H:i') ?? '—' }}</dd>
                </dl>

                <form action="{{ route('security.inout_permits.process_approval', $permit->id) }}" method="POST">
                    @csrf
                    <div class="form-group">
                        <label>Decision</label>
                        <select name="decision" class="form-control" required>
                            <option value="approved">Approve</option>
                            <option value="rejected">Reject</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Notes (optional)</label>
                        <textarea name="notes" class="form-control" rows="3"></textarea>
                    </div>
                    <button type="submit" class="btn btn-primary">Submit Decision</button>
                </form>
            </div>
        </div>
    </div>
@endsection
