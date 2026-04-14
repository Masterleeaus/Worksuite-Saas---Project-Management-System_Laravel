@extends('layouts.app')

@section('content')
<div class="content-wrapper">
    <div class="row mb-3">
        <div class="col-12 d-flex justify-content-between align-items-center">
            <h4>Add SMS Number</h4>
            <a href="{{ route('titanreach.sms.index') }}" class="btn btn-outline-secondary">← Back</a>
        </div>
    </div>

    <div class="row">
        <div class="col-md-6">
            <div class="card">
                <div class="card-body">
                    <form method="POST" action="{{ route('titanreach.sms.store') }}">
                        @csrf

                        <div class="form-group">
                            <label>Friendly Name <span class="text-danger">*</span></label>
                            <input type="text" name="name" class="form-control @error('name') is-invalid @enderror"
                                   value="{{ old('name') }}" placeholder="e.g. Notifications Line" required>
                            @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>

                        <div class="form-group">
                            <label>Phone Number <span class="text-danger">*</span></label>
                            <input type="text" name="phone_number" class="form-control @error('phone_number') is-invalid @enderror"
                                   value="{{ old('phone_number') }}" placeholder="+1234567890" maxlength="20" required>
                            @error('phone_number')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>

                        <div class="form-group">
                            <label>Twilio Account SID <small class="text-muted">(optional — overrides global)</small></label>
                            <input type="text" name="account_sid" class="form-control @error('account_sid') is-invalid @enderror"
                                   value="{{ old('account_sid') }}" placeholder="ACxxxxxxxx">
                            @error('account_sid')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>

                        <button type="submit" class="btn btn-success w-100">Save Number</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
