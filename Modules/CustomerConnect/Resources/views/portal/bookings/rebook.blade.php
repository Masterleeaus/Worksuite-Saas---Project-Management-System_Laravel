@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <div>
                    <h2 class="mb-1">Request a New Booking</h2>
                    <p class="text-muted mb-0">Submit a booking request and we'll confirm the details with you.</p>
                </div>
                @if(Route::has('customerconnect.portal.bookings.index'))
                    <a href="{{ route('customerconnect.portal.bookings.index') }}" class="btn btn-outline-secondary">
                        <i class="fa fa-arrow-left me-1"></i> Back
                    </a>
                @endif
            </div>

            <div class="card">
                <div class="card-body">
                    <form action="{{ route('customerconnect.portal.bookings.rebook.store') }}" method="POST">
                        @csrf

                        <div class="mb-3">
                            <label for="service_type" class="form-label fw-semibold">Service Type</label>
                            <select name="service_type" id="service_type" class="form-select @error('service_type') is-invalid @enderror" required>
                                <option value="">— Select a service —</option>
                                <option value="regular" {{ old('service_type', $lastBooking?->service_type) === 'regular' ? 'selected' : '' }}>Regular Cleaning</option>
                                <option value="deep_clean" {{ old('service_type', $lastBooking?->service_type) === 'deep_clean' ? 'selected' : '' }}>Deep Clean</option>
                                <option value="end_of_lease" {{ old('service_type', $lastBooking?->service_type) === 'end_of_lease' ? 'selected' : '' }}>End of Lease</option>
                                <option value="carpet" {{ old('service_type', $lastBooking?->service_type) === 'carpet' ? 'selected' : '' }}>Carpet Cleaning</option>
                                <option value="window" {{ old('service_type', $lastBooking?->service_type) === 'window' ? 'selected' : '' }}>Window Cleaning</option>
                            </select>
                            @error('service_type')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="preferred_date" class="form-label fw-semibold">Preferred Date</label>
                            <input type="date"
                                   name="preferred_date"
                                   id="preferred_date"
                                   class="form-control @error('preferred_date') is-invalid @enderror"
                                   value="{{ old('preferred_date') }}"
                                   min="{{ date('Y-m-d', strtotime('+1 day')) }}"
                                   required>
                            @error('preferred_date')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="notes" class="form-label fw-semibold">Special Instructions <span class="text-muted fw-normal">(optional)</span></label>
                            <textarea name="notes" id="notes" rows="4"
                                      class="form-control @error('notes') is-invalid @enderror"
                                      placeholder="Any specific requirements, access instructions, or notes for the cleaner…">{{ old('notes', $lastBooking?->description) }}</textarea>
                            @error('notes')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        @if($lastBooking)
                            <div class="alert alert-info d-flex align-items-center py-2 mb-3" role="alert">
                                <i class="fa fa-info-circle me-2"></i>
                                <span>Pre-filled from your last booking on {{ \Carbon\Carbon::parse($lastBooking->start_date_time)->format('d M Y') }}. Please update as needed.</span>
                            </div>
                        @endif

                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-primary">
                                <i class="fa fa-paper-plane me-1"></i> Submit Request
                            </button>
                            @if(Route::has('customerconnect.portal.bookings.index'))
                                <a href="{{ route('customerconnect.portal.bookings.index') }}" class="btn btn-outline-secondary">Cancel</a>
                            @endif
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
