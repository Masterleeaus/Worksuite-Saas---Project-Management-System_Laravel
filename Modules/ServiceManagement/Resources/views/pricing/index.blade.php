@extends('layouts.app')

@section('title', __('app.menu.servicePricing', [], 'Service Pricing Rules'))

@section('content')
    <div class="content-wrapper">
        <div class="row">
            <div class="col-sm-12">
                <div class="page-title-box">
                    <div class="row">
                        <div class="col">
                            <h4 class="page-title">{{ __('app.menu.servicePricing', [], 'Service Pricing Rules') }}</h4>
                        </div>
                        <div class="col-auto">
                            <a href="{{ route('services.pricing.create') }}" class="btn btn-primary btn-sm">
                                <i class="bi bi-plus"></i> {{ __('app.addNew') }}
                            </a>
                        </div>
                    </div>
                </div>

                <div class="card">
                    <div class="card-body">
                        <form method="GET" action="{{ route('services.pricing.index') }}" class="mb-4">
                            <div class="row g-2">
                                <div class="col-md-4">
                                    <select name="service_id" class="form-control form-control-sm">
                                        <option value="">{{ __('app.allServices', [], 'All Services') }}</option>
                                        @foreach ($services as $svc)
                                            <option value="{{ $svc->id }}" @selected(request('service_id') == $svc->id)>{{ $svc->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                @if ($zones->isNotEmpty())
                                    <div class="col-md-4">
                                        <select name="zone_id" class="form-control form-control-sm">
                                            <option value="">{{ __('app.allZones', [], 'All Zones') }}</option>
                                            @foreach ($zones as $zone)
                                                <option value="{{ $zone->id }}" @selected(request('zone_id') == $zone->id)>{{ $zone->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                @endif
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
                                        <th>{{ __('app.service', [], 'Service') }}</th>
                                        <th>{{ __('app.zone', [], 'Zone') }}</th>
                                        <th>{{ __('app.label', [], 'Label') }}</th>
                                        <th>{{ __('app.basePrice', [], 'Base Override') }}</th>
                                        <th>/Bed</th>
                                        <th>/Bath</th>
                                        <th>{{ __('app.minPrice', [], 'Min Price') }}</th>
                                        <th>{{ __('app.status') }}</th>
                                        <th>{{ __('app.action') }}</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($rules as $rule)
                                        <tr>
                                            <td>{{ $loop->iteration }}</td>
                                            <td>{{ optional($rule->service)->name ?? '-' }}</td>
                                            <td>{{ optional($rule->zone)->name ?? __('app.allZones', [], 'All') }}</td>
                                            <td>{{ $rule->label ?? '-' }}</td>
                                            <td>{{ $rule->base_price_override ? number_format($rule->base_price_override, 2) : '-' }}</td>
                                            <td>{{ $rule->per_bedroom_price ? number_format($rule->per_bedroom_price, 2) : '-' }}</td>
                                            <td>{{ $rule->per_bathroom_price ? number_format($rule->per_bathroom_price, 2) : '-' }}</td>
                                            <td>{{ $rule->min_price ? number_format($rule->min_price, 2) : '-' }}</td>
                                            <td>
                                                <span class="badge {{ $rule->is_active ? 'bg-success' : 'bg-secondary' }}">
                                                    {{ $rule->is_active ? __('app.active') : __('app.inactive') }}
                                                </span>
                                            </td>
                                            <td>
                                                <a href="{{ route('services.pricing.edit', $rule->id) }}" class="btn btn-sm btn-info">
                                                    <i class="bi bi-pencil"></i>
                                                </a>
                                                <form method="POST" action="{{ route('services.pricing.destroy', $rule->id) }}" class="d-inline"
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
                                            <td colspan="10" class="text-center">{{ __('app.noRecordFound') }}</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                        {{ $rules->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
