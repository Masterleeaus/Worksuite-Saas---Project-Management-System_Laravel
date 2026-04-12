@extends('layouts.app')

@section('content')
<div class="content-wrapper">
    <div class="d-flex justify-content-between align-items-center action-bar">
        <h4 class="f-21 font-weight-normal text-capitalize mb-0">
            @lang('titantheme::titantheme.edit_preset'): {{ $preset->name }}
        </h4>
        <a href="{{ route('titantheme.presets.index') }}" class="btn btn-outline-secondary btn-sm">
            <i class="fa fa-arrow-left mr-1"></i> @lang('app.back')
        </a>
    </div>

    <div class="card mt-3">
        <div class="card-body">
            <form method="POST" action="{{ route('titantheme.presets.update', $preset->id) }}">
                @csrf
                @method('PUT')
                @include('titantheme::presets._form', ['preset' => $preset])
                <div class="mt-4">
                    <button type="submit" class="btn btn-primary">@lang('app.save')</button>
                    <a href="{{ route('titantheme.presets.index') }}" class="btn btn-outline-secondary ml-2">@lang('app.cancel')</a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
