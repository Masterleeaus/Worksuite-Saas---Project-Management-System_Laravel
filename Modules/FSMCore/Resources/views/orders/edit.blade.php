@extends('fsmcore::layouts.master')

@section('fsm_content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <h2>Edit Order: {{ $order->name }}</h2>
    <a href="{{ route('fsmcore.orders.show', $order->id) }}" class="btn btn-outline-secondary">Back</a>
</div>

<div class="card">
    <div class="card-body">
        <form method="POST" action="{{ route('fsmcore.orders.update', $order->id) }}">
            @csrf
            @include('fsmcore::orders._form', ['order' => $order])
            <div class="mt-3">
                <button type="submit" class="btn btn-primary">Save Changes</button>
                <a href="{{ route('fsmcore.orders.show', $order->id) }}" class="btn btn-link">Cancel</a>
            </div>
        </form>
    </div>
</div>
@endsection
