@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row mb-4">
        <div class="col-12">
            <h2 class="mb-1">My Portal</h2>
            <p class="text-muted mb-0">Manage your bookings, invoices, properties and preferences.</p>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="row g-4">
        {{-- Next Booking --}}
        <div class="col-md-4">
            <div class="card h-100">
                <div class="card-body">
                    <h5 class="card-title"><i class="fa fa-calendar-check me-2 text-primary"></i>Next Booking</h5>
                    @if($nextBooking)
                        <p class="mb-1 fw-semibold">{{ $nextBooking->heading ?? 'Cleaning Service' }}</p>
                        <p class="text-muted mb-1">
                            {{ $nextBooking->start_date_time ? \Carbon\Carbon::parse($nextBooking->start_date_time)->format('D, d M Y \a\t g:ia') : 'Date TBC' }}
                        </p>
                        <span class="badge bg-primary">{{ ucfirst($nextBooking->booking_status ?? 'pending') }}</span>
                    @else
                        <p class="text-muted">No upcoming bookings.</p>
                        @if(Route::has('customerconnect.portal.bookings.rebook'))
                            <a href="{{ route('customerconnect.portal.bookings.rebook') }}" class="btn btn-sm btn-outline-primary mt-2">Book Now</a>
                        @endif
                    @endif
                </div>
                @if(Route::has('customerconnect.portal.bookings.index'))
                    <div class="card-footer">
                        <a href="{{ route('customerconnect.portal.bookings.index') }}" class="text-decoration-none small">View all bookings &rarr;</a>
                    </div>
                @endif
            </div>
        </div>

        {{-- Outstanding Invoices --}}
        <div class="col-md-4">
            <div class="card h-100">
                <div class="card-body">
                    <h5 class="card-title"><i class="fa fa-file-invoice-dollar me-2 text-warning"></i>Invoices</h5>
                    @if($outstandingInvoicesCount > 0)
                        <p class="mb-1">
                            <span class="badge bg-warning text-dark fs-6">{{ $outstandingInvoicesCount }}</span>
                            outstanding invoice{{ $outstandingInvoicesCount === 1 ? '' : 's' }}
                        </p>
                    @else
                        <p class="text-muted">No outstanding invoices.</p>
                    @endif
                </div>
                @if(Route::has('customerconnect.portal.invoices.index'))
                    <div class="card-footer">
                        <a href="{{ route('customerconnect.portal.invoices.index') }}" class="text-decoration-none small">View all invoices &rarr;</a>
                    </div>
                @endif
            </div>
        </div>

        {{-- Last Visit --}}
        <div class="col-md-4">
            <div class="card h-100">
                <div class="card-body">
                    <h5 class="card-title"><i class="fa fa-history me-2 text-success"></i>Last Visit</h5>
                    @if($lastBooking)
                        <p class="mb-1 fw-semibold">{{ $lastBooking->heading ?? 'Cleaning Service' }}</p>
                        <p class="text-muted mb-1">
                            {{ $lastBooking->start_date_time ? \Carbon\Carbon::parse($lastBooking->start_date_time)->format('D, d M Y') : '' }}
                        </p>
                        @if(Route::has('customerconnect.portal.reviews.create'))
                            <a href="{{ route('customerconnect.portal.reviews.create', ['booking_id' => $lastBooking->id]) }}" class="btn btn-sm btn-outline-success mt-2">Leave a Review</a>
                        @endif
                    @else
                        <p class="text-muted">No completed visits yet.</p>
                    @endif
                </div>
                @if(Route::has('customerconnect.portal.bookings.rebook'))
                    <div class="card-footer">
                        <a href="{{ route('customerconnect.portal.bookings.rebook') }}" class="text-decoration-none small">Book again &rarr;</a>
                    </div>
                @endif
            </div>
        </div>

        {{-- My Properties --}}
        <div class="col-md-4">
            <div class="card h-100">
                <div class="card-body">
                    <h5 class="card-title"><i class="fa fa-home me-2 text-info"></i>My Properties</h5>
                    <p class="mb-0">{{ $propertiesCount }} propert{{ $propertiesCount === 1 ? 'y' : 'ies' }} registered.</p>
                </div>
                @if(Route::has('customerconnect.portal.properties.index'))
                    <div class="card-footer">
                        <a href="{{ route('customerconnect.portal.properties.index') }}" class="text-decoration-none small">Manage properties &rarr;</a>
                    </div>
                @endif
            </div>
        </div>

        {{-- Quick Links --}}
        <div class="col-md-8">
            <div class="card h-100">
                <div class="card-body">
                    <h5 class="card-title"><i class="fa fa-th-large me-2 text-secondary"></i>Quick Links</h5>
                    <div class="row g-2 mt-1">
                        @if(Route::has('customerconnect.portal.bookings.rebook'))
                        <div class="col-6 col-md-4">
                            <a href="{{ route('customerconnect.portal.bookings.rebook') }}" class="btn btn-outline-primary w-100">
                                <i class="fa fa-redo me-1"></i> Rebook
                            </a>
                        </div>
                        @endif
                        @if(Route::has('customerconnect.portal.invoices.index'))
                        <div class="col-6 col-md-4">
                            <a href="{{ route('customerconnect.portal.invoices.index') }}" class="btn btn-outline-warning w-100">
                                <i class="fa fa-file-invoice me-1"></i> Invoices
                            </a>
                        </div>
                        @endif
                        @if(Route::has('customerconnect.portal.payments.index'))
                        <div class="col-6 col-md-4">
                            <a href="{{ route('customerconnect.portal.payments.index') }}" class="btn btn-outline-success w-100">
                                <i class="fa fa-credit-card me-1"></i> Payments
                            </a>
                        </div>
                        @endif
                        @if(Route::has('customerconnect.portal.properties.index'))
                        <div class="col-6 col-md-4">
                            <a href="{{ route('customerconnect.portal.properties.index') }}" class="btn btn-outline-info w-100">
                                <i class="fa fa-home me-1"></i> Properties
                            </a>
                        </div>
                        @endif
                        @if(Route::has('customerconnect.portal.preferences.index'))
                        <div class="col-6 col-md-4">
                            <a href="{{ route('customerconnect.portal.preferences.index') }}" class="btn btn-outline-secondary w-100">
                                <i class="fa fa-cog me-1"></i> Preferences
                            </a>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
