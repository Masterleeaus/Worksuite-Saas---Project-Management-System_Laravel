@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="mb-1">My Bookings</h2>
            <p class="text-muted mb-0">View upcoming and past cleaning appointments.</p>
        </div>
        @if(Route::has('customerconnect.portal.bookings.rebook'))
            <a href="{{ route('customerconnect.portal.bookings.rebook') }}" class="btn btn-primary">
                <i class="fa fa-plus me-1"></i> Request New Booking
            </a>
        @endif
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif
    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    {{-- Upcoming Bookings --}}
    <div class="card mb-4">
        <div class="card-header">
            <h5 class="mb-0"><i class="fa fa-calendar-alt me-2 text-primary"></i>Upcoming Bookings</h5>
        </div>
        <div class="card-body p-0">
            @if($upcoming->isEmpty())
                <div class="p-4 text-center text-muted">
                    <i class="fa fa-calendar fa-2x mb-2 d-block"></i>
                    No upcoming bookings.
                    @if(Route::has('customerconnect.portal.bookings.rebook'))
                        <br><a href="{{ route('customerconnect.portal.bookings.rebook') }}" class="btn btn-sm btn-primary mt-2">Book Now</a>
                    @endif
                </div>
            @else
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>Service</th>
                                <th>Date &amp; Time</th>
                                <th>Status</th>
                                <th>Address</th>
                                <th class="text-end">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($upcoming as $booking)
                            <tr>
                                <td>{{ $booking->heading ?? ($booking->service_type ? ucfirst(str_replace('_', ' ', $booking->service_type)) : 'Cleaning') }}</td>
                                <td>{{ $booking->start_date_time ? \Carbon\Carbon::parse($booking->start_date_time)->format('D d M Y, g:ia') : '—' }}</td>
                                <td><span class="badge bg-primary">{{ ucfirst($booking->booking_status ?? 'pending') }}</span></td>
                                <td>{{ $booking->service_address ?? '—' }}</td>
                                <td class="text-end">
                                    @if(in_array($booking->booking_status, ['pending', 'confirmed']) && Route::has('customerconnect.portal.bookings.cancel'))
                                        <form action="{{ route('customerconnect.portal.bookings.cancel', $booking->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Cancel this booking?')">
                                            @csrf
                                            <button type="submit" class="btn btn-sm btn-outline-danger">Cancel</button>
                                        </form>
                                    @endif
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>
    </div>

    {{-- Past Bookings --}}
    <div class="card">
        <div class="card-header">
            <h5 class="mb-0"><i class="fa fa-history me-2 text-secondary"></i>Past Bookings</h5>
        </div>
        <div class="card-body p-0">
            @if($past->isEmpty())
                <div class="p-4 text-center text-muted">No past bookings found.</div>
            @else
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>Service</th>
                                <th>Date</th>
                                <th>Status</th>
                                <th class="text-end">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($past as $booking)
                            <tr>
                                <td>{{ $booking->heading ?? ($booking->service_type ? ucfirst(str_replace('_', ' ', $booking->service_type)) : 'Cleaning') }}</td>
                                <td>{{ $booking->start_date_time ? \Carbon\Carbon::parse($booking->start_date_time)->format('D d M Y') : '—' }}</td>
                                <td>
                                    <span class="badge {{ $booking->booking_status === 'completed' ? 'bg-success' : 'bg-secondary' }}">
                                        {{ ucfirst($booking->booking_status ?? '') }}
                                    </span>
                                </td>
                                <td class="text-end">
                                    @if($booking->booking_status === 'completed' && Route::has('customerconnect.portal.reviews.create'))
                                        <a href="{{ route('customerconnect.portal.reviews.create', ['booking_id' => $booking->id]) }}" class="btn btn-sm btn-outline-success">Review</a>
                                    @endif
                                    @if(Route::has('customerconnect.portal.bookings.rebook'))
                                        <a href="{{ route('customerconnect.portal.bookings.rebook') }}" class="btn btn-sm btn-outline-primary">Rebook</a>
                                    @endif
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
