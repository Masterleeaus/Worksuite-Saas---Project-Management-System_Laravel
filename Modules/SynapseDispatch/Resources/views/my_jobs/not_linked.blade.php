@extends('synapsedispatch::layouts.master')

@section('synapse_content')
<div class="text-center py-5">
    <div class="display-1 mb-3">🔗</div>
    <h2>No Worker Account Linked</h2>
    <p class="text-muted mt-2">
        Your Worksuite account is not yet linked to a dispatch worker profile.<br>
        Please ask your administrator to link your account in the
        <a href="{{ route('synapsedispatch.workers.index') }}">Workers</a> section.
    </p>
</div>
@endsection
