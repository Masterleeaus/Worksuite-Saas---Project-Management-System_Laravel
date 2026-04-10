@extends('layouts.app')

@section('content')
<div class="container py-4" style="max-width:560px;">
    <div class="card shadow-sm text-center p-5">
        <div class="mb-3">
            <span class="display-3">⭐</span>
        </div>
        <h3 class="mb-2">Thank you for your feedback!</h3>
        <p class="text-muted mb-4">
            Your rating for <strong>{{ $order->name }}</strong> has been recorded.
            We really appreciate you taking the time to let us know how we did.
        </p>
        <div class="d-flex justify-content-center gap-3">
            <a href="{{ route('clientpulse.portal.history.index') }}" class="btn btn-primary">
                <i class="ti-arrow-left me-1"></i>Back to History
            </a>
            @if(config('clientpulse.allow_extras_requests', true))
                <a href="{{ route('clientpulse.portal.extras.create') }}" class="btn btn-outline-primary">
                    Request an Extra
                </a>
            @endif
        </div>
    </div>
</div>
@endsection
