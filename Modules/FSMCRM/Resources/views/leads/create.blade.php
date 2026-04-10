@extends('fsmcrm::layouts.master')

@section('fsmcrm_content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <h2>New Lead</h2>
    <a href="{{ route('fsmcrm.leads.index') }}" class="btn btn-outline-secondary">← Back</a>
</div>

<div class="card">
    <div class="card-body">
        <form method="POST" action="{{ route('fsmcrm.leads.store') }}">
            @csrf
            @include('fsmcrm::leads._form', ['lead' => null])
            <div class="mt-3">
                <button type="submit" class="btn btn-success">Create Lead</button>
                <a href="{{ route('fsmcrm.leads.index') }}" class="btn btn-outline-secondary ms-2">Cancel</a>
            </div>
        </form>
    </div>
</div>
@endsection
