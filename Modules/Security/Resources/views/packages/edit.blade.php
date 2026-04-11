@extends('layouts.app')

@section('content')
    <div class="content-wrapper">
        <div class="d-flex justify-content-between align-items-center action-bar mb-4">
            <h4 class="mb-0">@lang('app.edit') @lang('security::app.packages')</h4>
            <a href="{{ route('security.packages.show', $package->id) }}" class="btn btn-secondary btn-sm">
                <i class="fa fa-arrow-left mr-1"></i> @lang('app.back')
            </a>
        </div>

        <div class="card shadow-sm">
            <div class="card-body">
                <form action="{{ route('security.packages.update', $package->id) }}" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="row">
                        <div class="col-md-6 form-group">
                            <label>Sender Name</label>
                            <input type="text" name="sender_name" class="form-control" value="{{ $package->sender_name }}" required>
                        </div>
                        <div class="col-md-6 form-group">
                            <label>Tracking Number</label>
                            <input type="text" name="tracking_number" class="form-control" value="{{ $package->tracking_number }}">
                        </div>
                        <div class="col-md-6 form-group">
                            <label>Recipient</label>
                            <select name="user_id" class="form-control select-picker" data-live-search="true">
                                <option value="">— Select Recipient —</option>
                                @foreach ($users ?? [] as $user)
                                    <option value="{{ $user->id }}" {{ ($package->user_id ?? '') == $user->id ? 'selected' : '' }}>{{ $user->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6 form-group">
                            <label>@lang('app.status')</label>
                            <select name="status" class="form-control select-picker">
                                @foreach (['pending', 'received', 'collected'] as $s)
                                    <option value="{{ $s }}" {{ ($package->status ?? '') === $s ? 'selected' : '' }}>{{ ucfirst($s) }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-12 form-group">
                            <label>Description</label>
                            <textarea name="description" class="form-control" rows="3">{{ $package->description }}</textarea>
                        </div>
                    </div>
                    <button type="submit" class="btn btn-primary">@lang('app.save')</button>
                </form>
            </div>
        </div>
    </div>
@endsection
