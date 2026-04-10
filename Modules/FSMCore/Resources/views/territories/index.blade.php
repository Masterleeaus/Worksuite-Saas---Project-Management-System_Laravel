@extends('fsmcore::layouts.master')

@section('fsm_content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <h2>FSM Territories</h2>
    <a href="{{ route('fsmcore.territories.create') }}" class="btn btn-success">+ New Territory</a>
</div>

@foreach($territories as $territory)
    @include('fsmcore::territories._tree_item', ['territory' => $territory, 'depth' => 0])
@endforeach

@if($territories->isEmpty())
    <div class="text-muted text-center py-4">No territories yet.</div>
@endif
@endsection
