@extends('layouts.app')

@section('title', __('app.addNewAddon', [], 'Add New Add-on'))

@section('content')
    <div class="content-wrapper">
        <div class="row">
            <div class="col-sm-12">
                <div class="page-title-box">
                    <div class="row">
                        <div class="col">
                            <h4 class="page-title">{{ __('app.addNewAddon', [], 'Add New Add-on') }}</h4>
                        </div>
                        <div class="col-auto">
                            <a href="{{ route('services.addons.index') }}" class="btn btn-secondary btn-sm">
                                <i class="bi bi-arrow-left"></i> {{ __('app.back') }}
                            </a>
                        </div>
                    </div>
                </div>
                <div class="card">
                    <div class="card-body">
                        <form method="POST" action="{{ route('services.addons.store') }}">
                            @csrf
                            @include('servicemanagement::addons.partials.form', ['addon' => null])
                            <div class="mt-4">
                                <button type="submit" class="btn btn-primary">{{ __('app.save') }}</button>
                                <a href="{{ route('services.addons.index') }}" class="btn btn-secondary ms-2">{{ __('app.cancel') }}</a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
