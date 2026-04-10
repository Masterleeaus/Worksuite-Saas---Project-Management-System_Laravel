@extends('layouts.app')

@section('content')
    <div class="content-wrapper">
        <div class="container-fluid">

            <div class="d-flex justify-content-between align-items-center mb-3">
                <h1 class="h3 mb-0">Inspection #{{ $inspection->id }}</h1>
                <div>
                    @if(user()->permission('edit_inspection') == 'all')
                        <a href="{{ route('inspections.edit', $inspection->id) }}" class="btn btn-secondary mr-2">
                            <i class="fa fa-edit mr-1"></i> Edit
                        </a>
                    @endif
                    @if($inspection->status === \Modules\Inspection\Support\Enums\InspectionStatus::FAILED
                        && user()->permission('request_reclean') == 'all')
                        <form action="{{ route('inspections.request_reclean', $inspection->id) }}"
                              method="POST" class="d-inline mr-2">
                            @csrf
                            <button type="submit" class="btn btn-warning"
                                    onclick="return confirm('Request a re-clean for this inspection?')">
                                <i class="fa fa-redo mr-1"></i> Request Re-clean
                            </button>
                        </form>
                    @endif
                    @if(in_array($inspection->status, [
                            \Modules\Inspection\Support\Enums\InspectionStatus::IN_PROGRESS,
                            \Modules\Inspection\Support\Enums\InspectionStatus::FAILED,
                        ]) && user()->permission('approve_inspection') == 'all')
                        <form action="{{ route('inspections.approve', $inspection->id) }}"
                              method="POST" class="d-inline">
                            @csrf
                            <button type="submit" class="btn btn-success"
                                    onclick="return confirm('Approve this inspection as passed?')">
                                <i class="fa fa-check mr-1"></i> Approve
                            </button>
                        </form>
                    @endif
                </div>
            </div>

            @if(session('success'))
                <div class="alert alert-success alert-dismissible">
                    <button type="button" class="close" data-dismiss="alert">&times;</button>
                    {{ session('success') }}
                </div>
            @endif

            <div class="row">
                <div class="col-md-6">
                    <div class="card mb-4">
                        <div class="card-header font-weight-bold">Inspection Details</div>
                        <div class="card-body">
                            <dl class="row mb-0">
                                <dt class="col-sm-4">Inspector</dt>
                                <dd class="col-sm-8">{{ $inspection->inspector->name ?? '—' }}</dd>

                                <dt class="col-sm-4">Status</dt>
                                <dd class="col-sm-8">
                                    @php
                                        $badgeClass = \Modules\Inspection\Support\Enums\InspectionStatus::badgeClass($inspection->status);
                                        $label = \Modules\Inspection\Support\Enums\InspectionStatus::label($inspection->status);
                                    @endphp
                                    <span class="badge {{ $badgeClass }}">{{ $label }}</span>
                                </dd>

                                <dt class="col-sm-4">Score</dt>
                                <dd class="col-sm-8">
                                    {{ $inspection->score !== null ? number_format($inspection->score, 1) . ' / 10' : '—' }}
                                </dd>

                                <dt class="col-sm-4">Template</dt>
                                <dd class="col-sm-8">{{ $inspection->template->name ?? '—' }}</dd>

                                <dt class="col-sm-4">Inspected At</dt>
                                <dd class="col-sm-8">
                                    {{ $inspection->inspected_at ? $inspection->inspected_at->format('d M Y H:i') : '—' }}
                                </dd>

                                @if($inspection->approved_at)
                                    <dt class="col-sm-4">Approved By</dt>
                                    <dd class="col-sm-8">
                                        {{ $inspection->approvedBy->name ?? '—' }}
                                        <small class="text-muted">
                                            ({{ $inspection->approved_at->format('d M Y H:i') }})
                                        </small>
                                    </dd>
                                @endif

                                @if($inspection->notes)
                                    <dt class="col-sm-4">Notes</dt>
                                    <dd class="col-sm-8">{{ $inspection->notes }}</dd>
                                @endif
                            </dl>
                        </div>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="card mb-4">
                        <div class="card-header font-weight-bold">
                            Checklist Items
                            @if($inspection->items->count())
                                <span class="badge badge-success ml-2">
                                    {{ $inspection->passedItemCount() }} passed
                                </span>
                                <span class="badge badge-danger ml-1">
                                    {{ $inspection->failedItemCount() }} failed
                                </span>
                            @endif
                        </div>
                        <div class="card-body p-0">
                            @if($inspection->items->isEmpty())
                                <p class="p-3 text-muted mb-0">No checklist items recorded.</p>
                            @else
                                <ul class="list-group list-group-flush">
                                    @foreach($inspection->items as $item)
                                        <li class="list-group-item d-flex justify-content-between align-items-start">
                                            <div>
                                                <strong>{{ $item->area }}</strong>
                                                @if($item->notes)
                                                    <br><small class="text-muted">{{ $item->notes }}</small>
                                                @endif
                                            </div>
                                            <span class="badge {{ $item->passed ? 'badge-success' : 'badge-danger' }} ml-2">
                                                {{ $item->passed ? 'Pass' : 'Fail' }}
                                            </span>
                                        </li>
                                    @endforeach
                                </ul>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <a href="{{ route('inspections.index') }}" class="btn btn-link pl-0">
                &larr; Back to Inspections
            </a>

        </div>
    </div>
@endsection
