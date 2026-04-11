@extends('layouts.app')

@section('content')
    <div class="content-wrapper">
        <div class="d-flex justify-content-between align-items-center action-bar mb-4">
            <h4 class="mb-0">@lang('app.edit') @lang('security::app.inout_permits')</h4>
            <a href="{{ route('security.inout-permits.show', $permit->id) }}" class="btn btn-secondary btn-sm">
                <i class="fa fa-arrow-left mr-1"></i> @lang('app.back')
            </a>
        </div>

        <div class="card shadow-sm">
            <div class="card-body">
                <form action="{{ route('security.inout-permits.update', $permit->id) }}" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="row">
                        <div class="col-md-6 form-group">
                            <label>Visitor Name</label>
                            <input type="text" name="visitor_name" class="form-control" value="{{ $permit->visitor_name }}" required>
                        </div>
                        <div class="col-md-6 form-group">
                            <label>Visitor Phone</label>
                            <input type="text" name="visitor_phone" class="form-control" value="{{ $permit->visitor_phone }}">
                        </div>
                        <div class="col-md-12 form-group">
                            <label>Purpose</label>
                            <textarea name="purpose" class="form-control" rows="3">{{ $permit->purpose }}</textarea>
                        </div>
                        <div class="col-md-6 form-group">
                            <label>Entry Time</label>
                            <input type="datetime-local" name="entry_time" class="form-control" value="{{ $permit->entry_time?->format('Y-m-d\TH:i') }}">
                        </div>
                        <div class="col-md-6 form-group">
                            <label>Exit Time</label>
                            <input type="datetime-local" name="exit_time" class="form-control" value="{{ $permit->exit_time?->format('Y-m-d\TH:i') }}">
                        </div>
                    </div>
                    <button type="submit" class="btn btn-primary">@lang('app.save')</button>
                </form>
            </div>
        </div>
    </div>
@endsection
