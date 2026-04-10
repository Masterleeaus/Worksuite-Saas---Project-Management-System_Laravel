@extends('layouts.app')

@section('content')
    <div class="content-wrapper">
        <div class="container-fluid">

            <div class="d-flex justify-content-between align-items-center mb-3">
                <h1 class="h3 mb-0">@lang('inspection::sidebar.quality_inspections')</h1>
                @if(user()->permission('create_inspection') == 'all' || user()->permission('add_inspection') == 'all')
                    <a href="{{ route('inspections.create') }}" class="btn btn-primary">
                        <i class="fa fa-plus mr-1"></i> @lang('app.add') Inspection
                    </a>
                @endif
            </div>

            @if(session('success'))
                <div class="alert alert-success alert-dismissible">
                    <button type="button" class="close" data-dismiss="alert">&times;</button>
                    {{ session('success') }}
                </div>
            @endif

            <div class="card">
                <div class="card-body">
                    @if($inspections->count() === 0)
                        <div class="text-center py-5 text-muted">
                            <i class="fa fa-clipboard-check fa-3x mb-3"></i>
                            <p>No inspections found.</p>
                        </div>
                    @else
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Inspector</th>
                                        <th>Status</th>
                                        <th>Score</th>
                                        <th>Inspected At</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($inspections as $inspection)
                                        <tr>
                                            <td>{{ $inspection->id }}</td>
                                            <td>{{ $inspection->inspector->name ?? '—' }}</td>
                                            <td>
                                                @php
                                                    $badgeClass = \Modules\Inspection\Support\Enums\InspectionStatus::badgeClass($inspection->status);
                                                    $label = \Modules\Inspection\Support\Enums\InspectionStatus::label($inspection->status);
                                                @endphp
                                                <span class="badge {{ $badgeClass }}">{{ $label }}</span>
                                            </td>
                                            <td>{{ $inspection->score !== null ? number_format($inspection->score, 1) . ' / 10' : '—' }}</td>
                                            <td>{{ $inspection->inspected_at ? $inspection->inspected_at->format('d M Y H:i') : '—' }}</td>
                                            <td>
                                                <a href="{{ route('inspections.show', $inspection->id) }}"
                                                   class="btn btn-sm btn-outline-primary">
                                                    <i class="fa fa-eye"></i>
                                                </a>
                                                @if(user()->permission('edit_inspection') == 'all')
                                                    <a href="{{ route('inspections.edit', $inspection->id) }}"
                                                       class="btn btn-sm btn-outline-secondary">
                                                        <i class="fa fa-edit"></i>
                                                    </a>
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        <div class="mt-3">
                            {{ $inspections->links() }}
                        </div>
                    @endif
                </div>
            </div>

        </div>
    </div>
@endsection
