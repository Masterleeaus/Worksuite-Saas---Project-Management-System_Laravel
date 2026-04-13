@extends('layouts.app')

@section('content')
<div class="content-wrapper">
    <div class="row mb-3">
        <div class="col-12 d-flex justify-content-between align-items-center">
            <h4>Add WhatsApp Channel</h4>
            <a href="{{ route('titanreach.whatsapp.index') }}" class="btn btn-outline-secondary">← Back</a>
        </div>
    </div>

    <div class="row">
        <div class="col-md-6">
            <div class="card">
                <div class="card-body">
                    <form method="POST" action="{{ route('titanreach.whatsapp.store') }}">
                        @csrf

                        <div class="form-group">
                            <label>Name <span class="text-danger">*</span></label>
                            <input type="text" name="name" class="form-control @error('name') is-invalid @enderror"
                                   value="{{ old('name') }}" required>
                            @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>

                        <div class="form-group">
                            <label>Twilio Account SID <span class="text-danger">*</span></label>
                            <input type="text" name="account_sid" class="form-control @error('account_sid') is-invalid @enderror"
                                   value="{{ old('account_sid') }}" required>
                            @error('account_sid')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>

                        <div class="form-group">
                            <label>Twilio Auth Token <span class="text-danger">*</span></label>
                            <input type="password" name="auth_token" class="form-control @error('auth_token') is-invalid @enderror"
                                   value="{{ old('auth_token') }}" required>
                            @error('auth_token')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>

                        <div class="form-group">
                            <label>From Number <span class="text-danger">*</span></label>
                            <input type="text" name="from_number" class="form-control @error('from_number') is-invalid @enderror"
                                   value="{{ old('from_number') }}" placeholder="whatsapp:+1234567890" required>
                            @error('from_number')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>

                        <button type="submit" class="btn btn-success w-100">Save Channel</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
