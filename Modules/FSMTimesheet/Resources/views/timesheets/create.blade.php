@extends('fsmtimesheet::layouts.master')

@section('fsmtimesheet_content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <h2>Add Timesheet Line – Order: {{ $order->name }}</h2>
    <a href="{{ route('fsmtimesheet.timesheets.index', $order->id) }}" class="btn btn-outline-secondary">← Back</a>
</div>

<div class="card">
    <div class="card-body">
        <form method="POST" action="{{ route('fsmtimesheet.timesheets.store', $order->id) }}">
            @csrf
            @include('fsmtimesheet::timesheets._form')
            <button type="submit" class="btn btn-primary">Save</button>
        </form>
    </div>
</div>
@endsection
