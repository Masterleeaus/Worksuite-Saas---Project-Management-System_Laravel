@extends('layouts.app')

@section('content')
    <div class="content-wrapper">
        <div class="d-flex justify-content-between align-items-center action-bar mb-4">
            <h4 class="mb-0">Approve Work Permit — #{{ $permit->id }}</h4>
            <a href="{{ route('security.work-permits.show', $permit->id) }}" class="btn btn-secondary btn-sm">
                <i class="fa fa-arrow-left mr-1"></i> @lang('app.back')
            </a>
        </div>

        <div class="card shadow-sm">
            <div class="card-body">
                <dl class="row mb-4">
                    <dt class="col-sm-3">Contractor</dt><dd class="col-sm-9">{{ $permit->contractor_name }}</dd>
                    <dt class="col-sm-3">Company</dt><dd class="col-sm-9">{{ $permit->company_name ?? '—' }}</dd>
                    <dt class="col-sm-3">Work Description</dt><dd class="col-sm-9">{{ $permit->work_description }}</dd>
                </dl>

                <form action="{{ route('security.work_permits.process_approval', $permit->id) }}" method="POST">
                    @csrf
                    <div class="form-group">
                        <label>Decision</label>
                        <select name="decision" class="form-control" required>
                            <option value="approved">Approve</option>
                            <option value="rejected">Reject</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Rejection Reason (if rejecting)</label>
                        <textarea name="rejection_reason" class="form-control" rows="3"></textarea>
                    </div>
                    <button type="submit" class="btn btn-primary">Submit Decision</button>
                </form>
            </div>
        </div>
    </div>
@endsection
