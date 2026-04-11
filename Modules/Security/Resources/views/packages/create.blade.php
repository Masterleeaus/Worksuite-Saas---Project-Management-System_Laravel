@extends('layouts.app')

@section('content')
    <div class="content-wrapper">
        <div class="d-flex justify-content-between align-items-center action-bar mb-4">
            <h4 class="mb-0">@lang('app.create') @lang('security::app.packages')</h4>
            <a href="{{ route('security.packages.index') }}" class="btn btn-secondary btn-sm">
                <i class="fa fa-arrow-left mr-1"></i> @lang('app.back')
            </a>
        </div>

        <div class="card shadow-sm">
            <div class="card-body">
                <form action="{{ route('security.packages.store') }}" method="POST">
                    @csrf
                    <div class="row">
                        <div class="col-md-6 form-group">
                            <label>Sender Name</label>
                            <input type="text" name="sender_name" class="form-control" required>
                        </div>
                        <div class="col-md-6 form-group">
                            <label>Tracking Number</label>
                            <input type="text" name="tracking_number" class="form-control">
                        </div>
                        <div class="col-md-6 form-group">
                            <label>Recipient</label>
                            <select name="user_id" class="form-control select-picker" data-live-search="true">
                                <option value="">— Select Recipient —</option>
                                @foreach ($users ?? [] as $user)
                                    <option value="{{ $user->id }}">{{ $user->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6 form-group">
                            <label>Received At</label>
                            <input type="datetime-local" name="received_at" class="form-control">
                        </div>
                        <div class="col-md-12 form-group">
                            <label>Description</label>
                            <textarea name="description" class="form-control" rows="3"></textarea>
                        </div>
                    </div>
                    <button type="submit" class="btn btn-primary">@lang('app.save')</button>
                </form>
            </div>
        </div>
    </div>
@endsection
