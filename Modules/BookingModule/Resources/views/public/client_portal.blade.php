@extends('layouts.app')
@section('content')
<div class="container">
    <h2>{{ __('Client Portal') }}</h2>
    <p>{{ __('Upcoming bookings, recent requests, and status tracking.') }}</p>
    <div class="card"><div class="card-body">
        <table class="table">
            <thead><tr><th>ID</th><th>Status</th><th>Created</th></tr></thead>
            <tbody>
            @forelse($bookings as $booking)
                <tr><td>{{ $booking->id }}</td><td>{{ $booking->booking_status ?? $booking->status ?? 'pending' }}</td><td>{{ $booking->created_at ?? '' }}</td></tr>
            @empty
                <tr><td colspan="3">{{ __('No bookings found yet.') }}</td></tr>
            @endforelse
            </tbody>
        </table>
    </div></div>
</div>
@endsection
