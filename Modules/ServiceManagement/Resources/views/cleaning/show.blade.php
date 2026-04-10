@extends('layouts.app')

@section('title', $service->name)

@section('content')
    <div class="content-wrapper">
        <div class="row">
            <div class="col-sm-12">
                <div class="page-title-box">
                    <div class="row">
                        <div class="col">
                            <h4 class="page-title">{{ $service->name }}</h4>
                        </div>
                        <div class="col-auto">
                            <a href="{{ route('services.edit', $service->id) }}" class="btn btn-info btn-sm">
                                <i class="bi bi-pencil"></i> {{ __('app.edit') }}
                            </a>
                            <a href="{{ route('services.index') }}" class="btn btn-secondary btn-sm ms-1">
                                <i class="bi bi-arrow-left"></i> {{ __('app.back') }}
                            </a>
                        </div>
                    </div>
                </div>

                <div class="row g-3">
                    {{-- Service details --}}
                    <div class="col-md-8">
                        <div class="card h-100">
                            <div class="card-body">
                                <dl class="row">
                                    <dt class="col-sm-4">{{ __('app.name') }}</dt>
                                    <dd class="col-sm-8">{{ $service->name }}</dd>

                                    <dt class="col-sm-4">{{ __('app.category') }}</dt>
                                    <dd class="col-sm-8">{{ optional($service->category)->name ?? '-' }}</dd>

                                    <dt class="col-sm-4">{{ __('app.durationMinutes', [], 'Duration') }}</dt>
                                    <dd class="col-sm-8">{{ $service->duration_minutes ? $service->duration_minutes . ' min' : '-' }}</dd>

                                    <dt class="col-sm-4">{{ __('app.basePrice', [], 'Base Price') }}</dt>
                                    <dd class="col-sm-8">{{ $service->base_price ? number_format($service->base_price, 2) : '-' }}</dd>

                                    <dt class="col-sm-4">{{ __('app.frequency', [], 'Frequency') }}</dt>
                                    <dd class="col-sm-8">{{ $service->frequency ?? '-' }}</dd>

                                    <dt class="col-sm-4">{{ __('app.ecoFriendly', [], 'Eco-Friendly') }}</dt>
                                    <dd class="col-sm-8">
                                        @if ($service->eco_friendly)
                                            <span class="badge bg-success">{{ __('app.yes') }}</span>
                                        @else
                                            <span class="badge bg-secondary">{{ __('app.no') }}</span>
                                        @endif
                                    </dd>

                                    <dt class="col-sm-4">{{ __('app.status') }}</dt>
                                    <dd class="col-sm-8">
                                        @if ($service->is_active)
                                            <span class="badge bg-success">{{ __('app.active') }}</span>
                                        @else
                                            <span class="badge bg-danger">{{ __('app.inactive') }}</span>
                                        @endif
                                    </dd>

                                    @if ($service->short_description)
                                        <dt class="col-sm-4">{{ __('app.shortDescription', [], 'Short Description') }}</dt>
                                        <dd class="col-sm-8">{{ $service->short_description }}</dd>
                                    @endif

                                    @if ($service->description)
                                        <dt class="col-sm-4">{{ __('app.description') }}</dt>
                                        <dd class="col-sm-8">{!! nl2br(e($service->description)) !!}</dd>
                                    @endif
                                </dl>
                            </div>
                        </div>
                    </div>

                    {{-- Thumbnail --}}
                    <div class="col-md-4">
                        <div class="card h-100">
                            <div class="card-body text-center">
                                <img src="{{ $service->thumbnail_full_path }}" alt="{{ $service->name }}"
                                     class="img-fluid rounded" style="max-height:200px;">
                            </div>
                        </div>
                    </div>

                    {{-- Add-ons --}}
                    <div class="col-md-6">
                        <div class="card">
                            <div class="card-header d-flex justify-content-between align-items-center">
                                <h5 class="mb-0">{{ __('app.menu.serviceAddons', [], 'Add-ons') }}</h5>
                                <a href="{{ route('services.addons.create') }}" class="btn btn-sm btn-primary">
                                    <i class="bi bi-plus"></i>
                                </a>
                            </div>
                            <div class="card-body p-0">
                                <table class="table table-sm mb-0">
                                    <thead>
                                        <tr>
                                            <th>{{ __('app.name') }}</th>
                                            <th>{{ __('app.price') }}</th>
                                            <th>+{{ __('app.durationMinutes', [], 'Min') }}</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse ($service->addons as $addon)
                                            <tr>
                                                <td>{{ $addon->name }}</td>
                                                <td>{{ number_format($addon->price, 2) }}</td>
                                                <td>{{ $addon->duration_extra ?? '-' }}</td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="3" class="text-center text-muted">{{ __('app.noRecordFound') }}</td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>

                    {{-- Pricing rules --}}
                    <div class="col-md-6">
                        <div class="card">
                            <div class="card-header d-flex justify-content-between align-items-center">
                                <h5 class="mb-0">{{ __('app.menu.servicePricing', [], 'Pricing Rules') }}</h5>
                                <a href="{{ route('services.pricing.create') }}" class="btn btn-sm btn-primary">
                                    <i class="bi bi-plus"></i>
                                </a>
                            </div>
                            <div class="card-body p-0">
                                <table class="table table-sm mb-0">
                                    <thead>
                                        <tr>
                                            <th>{{ __('app.label', [], 'Label') }}</th>
                                            <th>{{ __('app.basePrice', [], 'Base') }}</th>
                                            <th>/Bed</th>
                                            <th>/Bath</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse ($service->pricingRules as $rule)
                                            <tr>
                                                <td>{{ $rule->label ?? '-' }}</td>
                                                <td>{{ $rule->base_price_override ? number_format($rule->base_price_override, 2) : '-' }}</td>
                                                <td>{{ $rule->per_bedroom_price ? number_format($rule->per_bedroom_price, 2) : '-' }}</td>
                                                <td>{{ $rule->per_bathroom_price ? number_format($rule->per_bathroom_price, 2) : '-' }}</td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="4" class="text-center text-muted">{{ __('app.noRecordFound') }}</td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
