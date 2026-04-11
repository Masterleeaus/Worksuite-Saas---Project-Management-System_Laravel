@extends('layouts/layoutMaster')

@section('title', 'Edit Chatbot')

@section('content')
<div class="container-fluid">
    <div class="d-flex align-items-center mb-4">
        <a href="{{ route('titanagents.chatbot.show', $chatbot) }}" class="btn btn-outline-secondary me-3">
            <i class="ti ti-arrow-left"></i>
        </a>
        <h4 class="mb-0">Edit: {{ $chatbot->name }}</h4>
    </div>

    <div class="card">
        <div class="card-body">
            <form method="POST" action="{{ route('titanagents.chatbot.update', $chatbot) }}">
                @csrf @method('PUT')
                @include('titanagents::chatbot._form')
                <div class="mt-4">
                    <button type="submit" class="btn btn-primary">Save Changes</button>
                    <a href="{{ route('titanagents.chatbot.show', $chatbot) }}" class="btn btn-outline-secondary ms-2">Cancel</a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
