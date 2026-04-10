@extends('layouts.app')

@section('title', __('app.menu.services'))

@section('content')
    <div class="content-wrapper">
        <div class="row">
            <div class="col-sm-12">
                <div class="page-title-box">
                    <div class="row">
                        <div class="col">
                            <h4 class="page-title">{{ __('app.menu.services') }}</h4>
                        </div>
                        <div class="col-auto align-self-center">
                            <a href="{{ route('services.create') }}" class="btn btn-primary btn-sm">
                                <i class="bi bi-plus"></i> {{ __('app.addNew') }}
                            </a>
                        </div>
                    </div>
                </div>
                <div class="card">
                    <div class="card-body">
                        <form method="GET" action="{{ route('services.index') }}" class="mb-4">
                            <div class="row g-2">
                                <div class="col-md-4">
                                    <input type="text" name="search" value="{{ request('search') }}"
                                           placeholder="{{ __('app.search') }}" class="form-control form-control-sm">
                                </div>
                                <div class="col-md-3">
                                    <select name="status" class="form-control form-control-sm">
                                        <option value="">{{ __('app.all') }}</option>
                                        <option value="1" @selected(request('status') === '1')>{{ __('app.active') }}</option>
                                        <option value="0" @selected(request('status') === '0')>{{ __('app.inactive') }}</option>
                                    </select>
                                </div>
                                <div class="col-md-3">
                                    <select name="frequency" class="form-control form-control-sm">
                                        <option value="">{{ __('app.allFrequencies', [], 'All Frequencies') }}</option>
                                        <option value="one_off" @selected(request('frequency') === 'one_off')>One-off</option>
                                        <option value="weekly" @selected(request('frequency') === 'weekly')>Weekly</option>
                                        <option value="fortnightly" @selected(request('frequency') === 'fortnightly')>Fortnightly</option>
                                        <option value="monthly" @selected(request('frequency') === 'monthly')>Monthly</option>
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
                                        <th>{{ __('app.category') }}</th>
                                        <th>{{ __('app.duration', [], 'Duration (min)') }}</th>
                                        <th>{{ __('app.basePrice', [], 'Base Price') }}</th>
                                        <th>{{ __('app.frequency', [], 'Frequency') }}</th>
                                        <th>{{ __('app.ecoFriendly', [], 'Eco') }}</th>
                                        <th>{{ __('app.status') }}</th>
                                        <th>{{ __('app.action') }}</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($services as $service)
                                        <tr>
                                            <td>{{ $loop->iteration }}</td>
                                            <td>{{ $service->name }}</td>
                                            <td>{{ optional($service->category)->name ?? '-' }}</td>
                                            <td>{{ $service->duration_minutes ?? '-' }}</td>
                                            <td>{{ $service->base_price ? number_format($service->base_price, 2) : '-' }}</td>
                                            <td>{{ $service->frequency ?? '-' }}</td>
                                            <td>
                                                @if ($service->eco_friendly)
                                                    <span class="badge bg-success">{{ __('app.yes') }}</span>
                                                @else
                                                    <span class="badge bg-secondary">{{ __('app.no') }}</span>
                                                @endif
                                            </td>
                                            <td>
                                                <form method="POST" action="{{ route('services.toggle', $service->id) }}" class="d-inline">
                                                    @csrf
                                                    @method('PATCH')
                                                    <button type="submit" class="btn btn-sm {{ $service->is_active ? 'btn-success' : 'btn-secondary' }}">
                                                        {{ $service->is_active ? __('app.active') : __('app.inactive') }}
                                                    </button>
                                                </form>
                                            </td>
                                            <td>
                                                <a href="{{ route('services.edit', $service->id) }}" class="btn btn-sm btn-info">
                                                    <i class="bi bi-pencil"></i>
                                                </a>
                                                <a href="{{ route('services.show', $service->id) }}" class="btn btn-sm btn-primary">
                                                    <i class="bi bi-eye"></i>
                                                </a>
                                                <form method="POST" action="{{ route('services.destroy', $service->id) }}" class="d-inline"
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
                                            <td colspan="9" class="text-center">{{ __('app.noRecordFound') }}</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>

                        {{ $services->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
