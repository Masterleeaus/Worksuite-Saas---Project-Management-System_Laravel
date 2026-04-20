@extends('fsmterritory::layouts.master')

@section('fsm_content')
<div class="mb-3">
    <h2>Edit Branch: {{ $branch->name }}</h2>
</div>

<form method="POST" action="{{ route('fsmterritory.branches.update', $branch->id) }}">
    @csrf
    @include('fsmterritory::branches._form')
</form>
@endsection
