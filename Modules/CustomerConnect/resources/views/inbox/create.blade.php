@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="d-flex align-items-center justify-content-between mb-3">
        <h4 class="mb-0">New Message</h4>
        <a href="{{ route('customerconnect.inbox.index') }}" class="btn btn-light">Back</a>
    </div>

    <div class="card">
        <div class="card-body">
            <form method="post" action="{{ route('customerconnect.inbox.store') }}">
                @csrf
                <div class="row g-3">
                    <div class="col-md-3">
                        <label class="form-label">Channel</label>
                        <select name="channel" class="form-control" required>
                            @foreach(['email'=>'Email','sms'=>'SMS','whatsapp'=>'WhatsApp','telegram'=>'Telegram'] as $k=>$v)
                                <option value="{{ $k }}">{{ $v }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Display name</label>
                        <input name="display_name" class="form-control" value="{{ old('display_name') }}">
                    </div>
                    <div class="col-md-5">
                        <label class="form-label">Email</label>
                        <input name="email" class="form-control" value="{{ old('email') }}">
                    </div>

                    <div class="col-md-4">
                        <label class="form-label">Phone (E.164 preferred)</label>
                        <input name="phone" class="form-control" value="{{ old('phone') }}" placeholder="+614xxxxxxxx">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Telegram chat id</label>
                        <input name="telegram_chat_id" class="form-control" value="{{ old('telegram_chat_id') }}">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Subject (email only)</label>
                        <input name="subject" class="form-control" value="{{ old('subject') }}">
                    </div>

                    <div class="col-12">
                        <label class="form-label">Message</label>
                        <textarea name="message" rows="5" class="form-control" required>{{ old('message') }}</textarea>
                    </div>

                    <div class="col-12">
                        <button class="btn btn-primary">Create thread & send</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
