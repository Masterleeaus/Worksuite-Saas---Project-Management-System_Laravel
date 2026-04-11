@extends('layouts.app')

@section('content')
    <div class="content-wrapper">
        <div class="d-flex justify-content-between align-items-center action-bar mb-4">
            <h4 class="mb-0">@lang('app.create') @lang('security::app.notes')</h4>
            <a href="{{ route('security.notes.index') }}" class="btn btn-secondary btn-sm">
                <i class="fa fa-arrow-left mr-1"></i> @lang('app.back')
            </a>
        </div>

        <div class="card shadow-sm">
            <div class="card-body">
                <form action="{{ route('security.notes.store') }}" method="POST">
                    @csrf
                    <div class="row">
                        <div class="col-md-8 form-group">
                            <label>Title</label>
                            <input type="text" name="title" class="form-control" required>
                        </div>
                        <div class="col-md-4 form-group">
                            <label>Incident Type</label>
                            <input type="text" name="incident_type" class="form-control">
                        </div>
                        <div class="col-md-12 form-group">
                            <label>Content</label>
                            <textarea name="content" class="form-control" rows="5" required></textarea>
                        </div>
                        <div class="col-md-4 form-group">
                            <label>@lang('app.status')</label>
                            <select name="status" class="form-control select-picker">
                                <option value="open">Open</option>
                                <option value="resolved">Resolved</option>
                            </select>
                        </div>
                    </div>
                    <button type="submit" class="btn btn-primary">@lang('app.save')</button>
                </form>
            </div>
        </div>
    </div>
@endsection
