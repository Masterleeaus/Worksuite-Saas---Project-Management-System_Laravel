@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-lg-7">
            <div class="mb-4">
                <h2 class="mb-1">Preferences &amp; Settings</h2>
                <p class="text-muted mb-0">Update your notification preferences and preferred cleaner.</p>
            </div>

            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            <div class="card">
                <div class="card-body">
                    <form action="{{ route('customerconnect.portal.preferences.update') }}" method="POST">
                        @csrf
                        @method('PUT')

                        <h5 class="mb-3">Notification Preferences</h5>

                        <div class="mb-3">
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" name="notify_email" id="notify_email"
                                       value="1" {{ $prefs->notify_email ? 'checked' : '' }}>
                                <label class="form-check-label" for="notify_email">
                                    <i class="fa fa-envelope me-1 text-primary"></i>
                                    Email notifications (booking confirmations, reminders, invoices)
                                </label>
                            </div>
                        </div>

                        <div class="mb-4">
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" name="notify_sms" id="notify_sms"
                                       value="1" {{ $prefs->notify_sms ? 'checked' : '' }}>
                                <label class="form-check-label" for="notify_sms">
                                    <i class="fa fa-sms me-1 text-success"></i>
                                    SMS notifications (cleaner on the way, arrival confirmation)
                                </label>
                            </div>
                        </div>

                        <hr>

                        <h5 class="mb-3">Cleaner Preference</h5>

                        <div class="mb-4">
                            <label for="preferred_cleaner_id" class="form-label fw-semibold">Preferred Cleaner</label>
                            <input type="number" name="preferred_cleaner_id" id="preferred_cleaner_id"
                                   class="form-control @error('preferred_cleaner_id') is-invalid @enderror"
                                   value="{{ old('preferred_cleaner_id', $prefs->preferred_cleaner_id) }}"
                                   placeholder="Cleaner ID (leave blank for no preference)">
                            <div class="form-text">Enter your preferred cleaner's ID. Leave blank to accept any available cleaner.</div>
                            @error('preferred_cleaner_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>

                        <hr>

                        <h5 class="mb-3">Special Instructions</h5>

                        <div class="mb-4">
                            <label for="special_instructions" class="form-label fw-semibold">Default Special Instructions</label>
                            <textarea name="special_instructions" id="special_instructions" rows="4"
                                      class="form-control @error('special_instructions') is-invalid @enderror"
                                      placeholder="Default instructions applied to all bookings…">{{ old('special_instructions', $prefs->special_instructions) }}</textarea>
                            @error('special_instructions')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>

                        <button type="submit" class="btn btn-primary">
                            <i class="fa fa-save me-1"></i> Save Preferences
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
