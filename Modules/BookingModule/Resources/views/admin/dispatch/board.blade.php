@extends('layouts.app')

@section('content')
<div class="content container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="mb-1">Dispatch board</h2>
            <p class="text-muted mb-0">Premium booking operations board for triage, scheduling, and live workload review.</p>
        </div>
        <div class="d-flex gap-2">
            <a href="{{ route('admin.booking.page-requests.index') }}" class="btn btn-outline-primary">Page requests</a>
            <a href="{{ route('admin.booking.pages.index') }}" class="btn btn-primary">Booking pages</a>
        </div>
    </div>

    <div class="row g-3">
        @foreach($columns as $statusKey => $label)
            <div class="col-lg col-md-6">
                <div class="card h-100">
                    <div class="card-header"><strong>{{ $label }}</strong></div>
                    <div class="card-body" style="min-height: 240px;">
                        @forelse($bookingsByStatus[$statusKey] as $booking)
                            <div class="border rounded p-3 mb-3 bg-light">
                                <div class="fw-semibold">#{{ $booking->readable_id ?? $booking->id }}</div>
                                <div class="text-muted small">{{ optional($booking->customer)->first_name }} {{ optional($booking->customer)->last_name }}</div>
                                <div class="small mt-2">{{ optional($booking->service_schedule)->format('d M Y H:i') }}</div>
                            </div>
                        @empty
                            <div class="text-muted">No bookings in {{ strtolower($label) }}.</div>
                        @endforelse
                    </div>
                </div>
            </div>
        @endforeach
    </div>

    <div class="card mt-4">
        <div class="card-header"><strong>Newest booking page requests</strong></div>
        <div class="card-body table-responsive">
            <table class="table mb-0">
                <thead><tr><th>ID</th><th>Page</th><th>Customer</th><th>Service</th><th>Date</th><th>Status</th></tr></thead>
                <tbody>
                @forelse($pageRequests as $requestRow)
                    <tr>
                        <td>#{{ $requestRow->id }}</td>
                        <td>{{ $requestRow->page_slug }}</td>
                        <td>{{ $requestRow->customer_name }}</td>
                        <td>{{ $requestRow->service_name ?: '—' }}</td>
                        <td>{{ optional($requestRow->preferred_date)->format('d M Y') ?: '—' }}</td>
                        <td>{{ ucfirst($requestRow->status) }}</td>
                    </tr>
                @empty
                    <tr><td colspan="6" class="text-center text-muted py-4">No page requests yet.</td></tr>
                @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
