@extends('layouts.app')

@section('content')
<div class="content-wrapper">
    <div class="row mb-3">
        <div class="col-12">
            <h4>{{ isset($segment) ? 'Edit Segment' : 'Create Segment' }}</h4>
        </div>
    </div>

    <div class="card">
        <div class="card-body">
            <form method="POST" action="{{ isset($segment) ? route('titanreach.segments.update', $segment->id) : route('titanreach.segments.store') }}">
                @csrf

                @if ($errors->any())
                    <div class="alert alert-danger">
                        <ul class="mb-0">@foreach ($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
                    </div>
                @endif

                <div class="form-group">
                    <label>Name <span class="text-danger">*</span></label>
                    <input type="text" name="name" class="form-control" value="{{ old('name', $segment->name ?? '') }}" required>
                </div>
                <div class="form-group">
                    <label>Description</label>
                    <textarea name="description" class="form-control" rows="2">{{ old('description', $segment->description ?? '') }}</textarea>
                </div>

                <button type="submit" class="btn btn-primary">{{ isset($segment) ? 'Update' : 'Create' }} Segment</button>
                <a href="{{ route('titanreach.segments.index') }}" class="btn btn-secondary ml-2">Cancel</a>
            </form>
        </div>
    </div>
</div>
@endsection
