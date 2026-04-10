@extends('fsmworkflow::layouts.master')

@section('fsmworkflow_content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <h2>New Job Size Tier</h2>
    <a href="{{ route('fsmworkflow.sizes.index') }}" class="btn btn-outline-secondary">← Back</a>
</div>

<div class="card">
    <div class="card-body">
        <form method="POST" action="{{ route('fsmworkflow.sizes.store') }}">
            @csrf
            @include('fsmworkflow::sizes._form', ['size' => null])
            <div class="mt-3">
                <button type="submit" class="btn btn-success">Create</button>
                <a href="{{ route('fsmworkflow.sizes.index') }}" class="btn btn-outline-secondary ms-2">Cancel</a>
            </div>
        </form>
    </div>
</div>
@endsection
