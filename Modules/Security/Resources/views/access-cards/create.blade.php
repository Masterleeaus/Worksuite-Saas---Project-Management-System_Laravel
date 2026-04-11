@extends('layouts.app')

@section('content')
    <div class="content-wrapper">
        <div class="d-flex justify-content-between align-items-center action-bar mb-4">
            <h4 class="mb-0">@lang('app.create') @lang('security::app.access_cards')</h4>
            <a href="{{ route('security.access-cards.index') }}" class="btn btn-secondary btn-sm">
                <i class="fa fa-arrow-left mr-1"></i> @lang('app.back')
            </a>
        </div>

        <div class="card shadow-sm">
            <div class="card-body">
                <form action="{{ route('security.access-cards.store') }}" method="POST">
                    @csrf
                    <div class="row">
                        <div class="col-md-6 form-group">
                            <label>@lang('app.user')</label>
                            <select name="user_id" class="form-control select-picker" data-live-search="true">
                                @foreach ($users ?? [] as $user)
                                    <option value="{{ $user->id }}">{{ $user->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6 form-group">
                            <label>Card Number</label>
                            <input type="text" name="card_number" class="form-control" required>
                        </div>
                        <div class="col-md-6 form-group">
                            <label>Card Type</label>
                            <input type="text" name="card_type" class="form-control">
                        </div>
                        <div class="col-md-6 form-group">
                            <label>@lang('app.status')</label>
                            <select name="status" class="form-control select-picker">
                                <option value="active">Active</option>
                                <option value="inactive">Inactive</option>
                                <option value="expired">Expired</option>
                            </select>
                        </div>
                        <div class="col-md-6 form-group">
                            <label>Issued Date</label>
                            <input type="date" name="issued_date" class="form-control">
                        </div>
                        <div class="col-md-6 form-group">
                            <label>Expiry Date</label>
                            <input type="date" name="expiry_date" class="form-control">
                        </div>
                        <div class="col-md-12 form-group">
                            <label>Notes</label>
                            <textarea name="notes" class="form-control" rows="3"></textarea>
                        </div>
                    </div>
                    <button type="submit" class="btn btn-primary">@lang('app.save')</button>
                </form>
            </div>
        </div>
    </div>
@endsection
