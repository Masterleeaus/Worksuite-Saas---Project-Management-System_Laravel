@extends('layouts.app')

@section('content')
    <div class="content-wrapper">
        <div class="d-flex justify-content-between align-items-center action-bar mb-4">
            <h4 class="mb-0">@lang('app.edit') @lang('security::app.parking')</h4>
            <a href="{{ route('security.parking.show', $record->id) }}" class="btn btn-secondary btn-sm">
                <i class="fa fa-arrow-left mr-1"></i> @lang('app.back')
            </a>
        </div>

        <div class="card shadow-sm">
            <div class="card-body">
                <form action="{{ route('security.parking.update', $record->id) }}" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="row">
                        <div class="col-md-6 form-group">
                            <label>Vehicle Plate</label>
                            <input type="text" name="vehicle_plate" class="form-control" value="{{ $record->vehicle_plate }}" required>
                        </div>
                        <div class="col-md-6 form-group">
                            <label>Vehicle Type</label>
                            <input type="text" name="vehicle_type" class="form-control" value="{{ $record->vehicle_type }}">
                        </div>
                        <div class="col-md-6 form-group">
                            <label>Bay Number</label>
                            <input type="text" name="bay_number" class="form-control" value="{{ $record->bay_number }}">
                        </div>
                        <div class="col-md-6 form-group">
                            <label>Entry Time</label>
                            <input type="datetime-local" name="entry_time" class="form-control" value="{{ $record->entry_time?->format('Y-m-d\TH:i') }}">
                        </div>
                        <div class="col-md-6 form-group">
                            <label>Exit Time</label>
                            <input type="datetime-local" name="exit_time" class="form-control" value="{{ $record->exit_time?->format('Y-m-d\TH:i') }}">
                        </div>
                        <div class="col-md-6 form-group">
                            <label>@lang('app.status')</label>
                            <select name="status" class="form-control select-picker">
                                @foreach (['parked', 'exited'] as $s)
                                    <option value="{{ $s }}" {{ ($record->status ?? '') === $s ? 'selected' : '' }}>{{ ucfirst($s) }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <button type="submit" class="btn btn-primary">@lang('app.save')</button>
                </form>
            </div>
        </div>
    </div>
@endsection
