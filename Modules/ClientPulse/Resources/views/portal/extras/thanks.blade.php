@extends('layouts.app')

@section('content')
<div class="container py-4" style="max-width:560px;">
    <div class="card shadow-sm text-center p-5">
        <div class="mb-3">
            <span class="display-3">✅</span>
        </div>
        <h3 class="mb-2">Extras Request Submitted!</h3>
        <p class="text-muted mb-4">
            Your request has been received and our team will make sure it's added to your next scheduled clean.
        </p>
        <div class="d-flex justify-content-center gap-3">
            <a href="{{ route('clientpulse.portal.history.index') }}" class="btn btn-primary">
                <i class="ti-arrow-left me-1"></i>Back to History
            </a>
        </div>
    </div>
</div>
@endsection
