@extends('layouts.app')

@section('content')
    <div class="content-wrapper">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h3 class="heading-h1">@lang('projectroadmap::app.roadmap.editItem')</h3>
            <a href="{{ route('roadmap.admin.index') }}" class="btn btn-secondary">
                <i class="fa fa-arrow-left mr-1"></i> @lang('app.back')
            </a>
        </div>

        <div class="card">
            <div class="card-body">
                <form id="roadmap-item-form" method="POST" action="{{ route('roadmap.admin.update', $item->id) }}">
                    @csrf
                    @method('PUT')
                    <div class="row">
                        <div class="col-md-8">
                            <div class="form-group">
                                <label>@lang('app.name') <span class="text-danger">*</span></label>
                                <input type="text" name="name" class="form-control" required value="{{ old('name', $item->name) }}">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>@lang('app.status') <span class="text-danger">*</span></label>
                                <select name="status" class="form-control select-picker" required>
                                    @foreach($statuses as $key => $label)
                                        <option value="{{ $key }}" {{ old('status', $item->status) == $key ? 'selected' : '' }}>{{ $label }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>@lang('projectroadmap::app.roadmap.category')</label>
                                <input type="text" name="category" class="form-control" value="{{ old('category', $item->category) }}">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>@lang('projectroadmap::app.roadmap.targetRelease')</label>
                                <input type="text" name="target_release" class="form-control" placeholder="e.g. Q3 2026" value="{{ old('target_release', $item->target_release) }}">
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="form-group">
                                <label>@lang('app.description')</label>
                                <textarea name="description" class="form-control" rows="3">{{ old('description', $item->description) }}</textarea>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="form-group">
                                <label>@lang('projectroadmap::app.roadmap.releaseNotes')</label>
                                <textarea name="release_notes" class="form-control" rows="3">{{ old('release_notes', $item->release_notes) }}</textarea>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="form-group">
                                <div class="custom-control custom-switch">
                                    <input type="checkbox" class="custom-control-input" id="is_public" name="is_public" value="1"
                                        {{ old('is_public', $item->is_public) ? 'checked' : '' }}>
                                    <label class="custom-control-label" for="is_public">
                                        @lang('projectroadmap::app.roadmap.isPublic')
                                    </label>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-12 text-right">
                            <button type="submit" class="btn btn-primary">@lang('app.update')</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
