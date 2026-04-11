@extends('layouts.app')

@section('content')
    <div class="content-wrapper">
        <div class="d-flex justify-content-between align-items-center action-bar mb-3">
            <h4 class="mb-0">@lang('staffcompliance::compliance.compliance_dashboard')</h4>
            <div>
                <a href="{{ route('compliance.documents.index') }}" class="btn btn-outline-secondary btn-sm mr-2">
                    <i class="fa fa-file-alt mr-1"></i>@lang('staffcompliance::compliance.compliance_docs')
                </a>
                @if(user()->permission('manage_compliance_document_types') != 'none')
                    <a href="{{ route('compliance.types.index') }}" class="btn btn-outline-secondary btn-sm">
                        <i class="fa fa-cogs mr-1"></i>@lang('staffcompliance::compliance.document_types')
                    </a>
                @endif
            </div>
        </div>

        {{-- Summary Cards --}}
        <div class="row mb-4">
            <div class="col-md-3 col-sm-6 mb-3">
                <div class="card border-danger shadow-sm text-center">
                    <div class="card-body p-3">
                        <h3 class="mb-0 text-danger">{{ $summary['red'] }}</h3>
                        <small class="text-muted"><i class="fa fa-exclamation-circle text-danger mr-1"></i>@lang('staffcompliance::compliance.expired')</small>
                    </div>
                </div>
            </div>
            <div class="col-md-3 col-sm-6 mb-3">
                <div class="card border-warning shadow-sm text-center">
                    <div class="card-body p-3">
                        <h3 class="mb-0 text-warning">{{ $summary['orange'] }}</h3>
                        <small class="text-muted"><i class="fa fa-clock text-warning mr-1"></i>@lang('staffcompliance::compliance.expiring_soon')</small>
                    </div>
                </div>
            </div>
            <div class="col-md-3 col-sm-6 mb-3">
                <div class="card border-info shadow-sm text-center">
                    <div class="card-body p-3">
                        <h3 class="mb-0 text-info">{{ $summary['yellow'] }}</h3>
                        <small class="text-muted"><i class="fa fa-exclamation-triangle text-info mr-1"></i>@lang('staffcompliance::compliance.missing_mandatory')</small>
                    </div>
                </div>
            </div>
            <div class="col-md-3 col-sm-6 mb-3">
                <div class="card border-success shadow-sm text-center">
                    <div class="card-body p-3">
                        <h3 class="mb-0 text-success">{{ $summary['green'] }}</h3>
                        <small class="text-muted"><i class="fa fa-check-circle text-success mr-1"></i>@lang('staffcompliance::compliance.fully_compliant')</small>
                    </div>
                </div>
            </div>
        </div>

        {{-- Workers Table --}}
        <div class="d-flex flex-column w-tables rounded bg-white">
            <x-datatable.table class="border-0">
                <x-slot name="thead">
                    <tr>
                        <th>@lang('staffcompliance::compliance.worker')</th>
                        <th>@lang('staffcompliance::compliance.status')</th>
                        <th>@lang('staffcompliance::compliance.expired')</th>
                        <th>@lang('staffcompliance::compliance.expiring_soon')</th>
                        <th>@lang('staffcompliance::compliance.missing_mandatory')</th>
                        <th class="text-right">@lang('app.action')</th>
                    </tr>
                </x-slot>
                <x-slot name="tbody">
                    @forelse ($statuses as $row)
                        @php
                            $worker  = $row['worker'];
                            $badgeClass = match($row['status']) {
                                'red'    => 'badge-danger',
                                'orange' => 'badge-warning',
                                'yellow' => 'badge-info',
                                default  => 'badge-success',
                            };
                            $statusLabel = match($row['status']) {
                                'red'    => __('staffcompliance::compliance.expired'),
                                'orange' => __('staffcompliance::compliance.expiring_soon'),
                                'yellow' => __('staffcompliance::compliance.missing_mandatory'),
                                default  => __('staffcompliance::compliance.fully_compliant'),
                            };
                        @endphp
                        <tr>
                            <td>{{ $worker->name }}</td>
                            <td><span class="badge {{ $badgeClass }}">{{ $statusLabel }}</span></td>
                            <td>
                                @foreach($row['expired'] as $doc)
                                    <span class="badge badge-danger mr-1">{{ $doc->documentType?->name }}</span>
                                @endforeach
                                @if(count($row['expired']) === 0) <span class="text-muted">—</span> @endif
                            </td>
                            <td>
                                @foreach($row['expiring'] as $doc)
                                    <span class="badge badge-warning mr-1">{{ $doc->documentType?->name }}</span>
                                @endforeach
                                @if(count($row['expiring']) === 0) <span class="text-muted">—</span> @endif
                            </td>
                            <td>
                                @foreach($row['missing'] as $type)
                                    <span class="badge badge-secondary mr-1">{{ $type->name }}</span>
                                @endforeach
                                @if(count($row['missing']) === 0) <span class="text-muted">—</span> @endif
                            </td>
                            <td class="text-right">
                                <a href="{{ route('compliance.documents.index', ['worker_id' => $worker->id]) }}"
                                   class="btn btn-sm btn-outline-primary">
                                    <i class="fa fa-eye"></i>
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center text-muted py-4">
                                @lang('app.no_result_found')
                            </td>
                        </tr>
                    @endforelse
                </x-slot>
            </x-datatable.table>
        </div>
    </div>
@endsection
