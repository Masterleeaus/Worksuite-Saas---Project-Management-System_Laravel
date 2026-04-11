@extends('layouts.app')

@section('content')
    <div class="content-wrapper">
        <div class="d-flex justify-content-between align-items-center action-bar mb-4">
            <h4 class="mb-0">@lang('app.edit') @lang('security::app.work_permits')</h4>
            <a href="{{ route('security.work-permits.show', $permit->id) }}" class="btn btn-secondary btn-sm">
                <i class="fa fa-arrow-left mr-1"></i> @lang('app.back')
            </a>
        </div>

        <div class="card shadow-sm">
            <div class="card-body">
                <form action="{{ route('security.work-permits.update', $permit->id) }}" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="row">
                        <div class="col-md-6 form-group">
                            <label>Contractor Name</label>
                            <input type="text" name="contractor_name" class="form-control" value="{{ $permit->contractor_name }}" required>
                        </div>
                        <div class="col-md-6 form-group">
                            <label>Company Name</label>
                            <input type="text" name="company_name" class="form-control" value="{{ $permit->company_name }}">
                        </div>
                        <div class="col-md-12 form-group">
                            <label>Work Description</label>
                            <textarea name="work_description" class="form-control" rows="3">{{ $permit->work_description }}</textarea>
                        </div>
                        <div class="col-md-6 form-group">
                            <label>Start Date</label>
                            <input type="date" name="start_date" class="form-control" value="{{ $permit->start_date?->format('Y-m-d') }}">
                        </div>
                        <div class="col-md-6 form-group">
                            <label>End Date</label>
                            <input type="date" name="end_date" class="form-control" value="{{ $permit->end_date?->format('Y-m-d') }}">
                        </div>
                    </div>
                    <button type="submit" class="btn btn-primary">@lang('app.save')</button>
                </form>
            </div>
        </div>
    </div>
@endsection
