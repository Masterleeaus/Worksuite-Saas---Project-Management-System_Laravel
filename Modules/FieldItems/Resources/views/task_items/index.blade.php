@extends('layouts.app')

@section('content')
    <div class="content-wrapper">
        <div class="row">
            <div class="col-sm-12">
                <h4 class="page-title">@lang('fielditems::app.menu.jobConsumption')</h4>
            </div>
        </div>

        <div class="row">
            <div class="col-sm-12">
                <div class="card">
                    <div class="card-body">

                        <table class="table table-hover table-responsive-sm" id="task-items-table">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>@lang('app.item')</th>
                                    <th>@lang('app.quantity')</th>
                                    <th>@lang('app.price')</th>
                                    <th>@lang('app.total')</th>
                                    <th>@lang('app.action')</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($taskItems as $ti)
                                    <tr id="task-item-row-{{ $ti->id }}">
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ $ti->item ? $ti->item->name : '—' }}</td>
                                        <td>{{ $ti->quantity }}</td>
                                        <td>{{ currency_format($ti->unit_price) }}</td>
                                        <td>{{ currency_format($ti->quantity * $ti->unit_price) }}</td>
                                        <td>
                                            <a href="javascript:;"
                                               class="btn btn-danger btn-sm delete-task-item"
                                               data-id="{{ $ti->id }}"
                                               data-url="{{ route('task-items.destroy', $ti->id) }}">
                                                <i class="fa fa-trash"></i>
                                            </a>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="text-center">@lang('messages.noRecordFound')</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>

                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
