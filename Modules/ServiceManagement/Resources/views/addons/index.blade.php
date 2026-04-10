@extends('layouts.app')

@section('title', __('app.menu.serviceAddons', [], 'Service Add-ons'))

@section('content')
    <div class="content-wrapper">
        <div class="row">
            <div class="col-sm-12">
                <div class="page-title-box">
                    <div class="row">
                        <div class="col">
                            <h4 class="page-title">{{ __('app.menu.serviceAddons', [], 'Service Add-ons') }}</h4>
                        </div>
                        <div class="col-auto">
                            <a href="{{ route('services.addons.create') }}" class="btn btn-primary btn-sm">
                                <i class="bi bi-plus"></i> {{ __('app.addNew') }}
                            </a>
                        </div>
                    </div>
                </div>

                <div class="card">
                    <div class="card-body">
                        <form method="GET" action="{{ route('services.addons.index') }}" class="mb-4">
                            <div class="row g-2">
                                <div class="col-md-4">
                                    <select name="service_id" class="form-control form-control-sm">
                                        <option value="">{{ __('app.allServices', [], 'All Services') }}</option>
                                        @foreach ($services as $svc)
                                            <option value="{{ $svc->id }}" @selected(request('service_id') == $svc->id)>{{ $svc->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-2">
                                    <button type="submit" class="btn btn-secondary btn-sm w-100">{{ __('app.filter') }}</button>
                                </div>
                            </div>
                        </form>

                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>{{ __('app.name') }}</th>
                                        <th>{{ __('app.service', [], 'Service') }}</th>
                                        <th>{{ __('app.price') }}</th>
                                        <th>{{ __('app.durationExtra', [], 'Extra Duration (min)') }}</th>
                                        <th>{{ __('app.status') }}</th>
                                        <th>{{ __('app.action') }}</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($addons as $addon)
                                        <tr>
                                            <td>{{ $loop->iteration }}</td>
                                            <td>{{ $addon->name }}</td>
                                            <td>{{ optional($addon->service)->name ?? __('app.allServices', [], 'All Services') }}</td>
                                            <td>{{ number_format($addon->price, 2) }}</td>
                                            <td>{{ $addon->duration_extra ?? '-' }}</td>
                                            <td>
                                                <span class="badge {{ $addon->is_active ? 'bg-success' : 'bg-secondary' }}">
                                                    {{ $addon->is_active ? __('app.active') : __('app.inactive') }}
                                                </span>
                                            </td>
                                            <td>
                                                <a href="{{ route('services.addons.edit', $addon->id) }}" class="btn btn-sm btn-info">
                                                    <i class="bi bi-pencil"></i>
                                                </a>
                                                <form method="POST" action="{{ route('services.addons.destroy', $addon->id) }}" class="d-inline"
                                                      onsubmit="return confirm('{{ __('app.areYouSure') }}')">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-sm btn-danger">
                                                        <i class="bi bi-trash"></i>
                                                    </button>
                                                </form>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="7" class="text-center">{{ __('app.noRecordFound') }}</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                        {{ $addons->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
