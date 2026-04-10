@extends('fsmcrm::layouts.master')

@section('fsmcrm_content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <h2>Edit Lead: {{ $lead->name }}</h2>
    <div class="d-flex gap-2">
        <a href="{{ route('fsmcrm.leads.show', $lead->id) }}" class="btn btn-outline-secondary">← Back</a>
    </div>
</div>

<div class="card">
    <div class="card-body">
        <form method="POST" action="{{ route('fsmcrm.leads.update', $lead->id) }}">
            @csrf
            @include('fsmcrm::leads._form', ['lead' => $lead])
            <div class="mt-3">
                <button type="submit" class="btn btn-primary">Save Changes</button>
                <a href="{{ route('fsmcrm.leads.show', $lead->id) }}" class="btn btn-outline-secondary ms-2">Cancel</a>
            </div>
        </form>
    </div>
</div>
@endsection
