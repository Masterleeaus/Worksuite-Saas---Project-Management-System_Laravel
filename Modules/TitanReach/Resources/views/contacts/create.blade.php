@extends('layouts.app')

@section('content')
<div class="content-wrapper">
    <div class="row mb-3">
        <div class="col-12">
            <h4>{{ isset($contact) ? 'Edit Contact' : 'Create Contact' }}</h4>
        </div>
    </div>

    <div class="card">
        <div class="card-body">
            <form method="POST" action="{{ isset($contact) ? route('titanreach.contacts.update', $contact->id) : route('titanreach.contacts.store') }}">
                @csrf

                @if ($errors->any())
                    <div class="alert alert-danger">
                        <ul class="mb-0">@foreach ($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
                    </div>
                @endif

                <div class="form-group">
                    <label>Name <span class="text-danger">*</span></label>
                    <input type="text" name="name" class="form-control" value="{{ old('name', $contact->name ?? '') }}" required>
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Phone</label>
                            <input type="text" name="phone" class="form-control" value="{{ old('phone', $contact->phone ?? '') }}" placeholder="+1234567890">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Email</label>
                            <input type="email" name="email" class="form-control" value="{{ old('email', $contact->email ?? '') }}">
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>WhatsApp Number</label>
                            <input type="text" name="whatsapp_number" class="form-control" value="{{ old('whatsapp_number', $contact->whatsapp_number ?? '') }}" placeholder="whatsapp:+1234567890">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Telegram Chat ID</label>
                            <input type="text" name="telegram_chat_id" class="form-control" value="{{ old('telegram_chat_id', $contact->telegram_chat_id ?? '') }}">
                        </div>
                    </div>
                </div>

                <button type="submit" class="btn btn-primary">{{ isset($contact) ? 'Update' : 'Create' }} Contact</button>
                <a href="{{ route('titanreach.contacts.index') }}" class="btn btn-secondary ml-2">Cancel</a>
            </form>
        </div>
    </div>
</div>
@endsection
