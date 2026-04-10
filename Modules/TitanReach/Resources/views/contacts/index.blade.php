@extends('layouts.app')

@section('content')
<div class="content-wrapper">
    <div class="row mb-3">
        <div class="col-12 d-flex justify-content-between align-items-center">
            <h4>Contacts</h4>
            <a href="{{ route('titanreach.contacts.create') }}" class="btn btn-success">+ New Contact</a>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <form method="GET" action="{{ route('titanreach.contacts.index') }}" class="row mb-3">
        <div class="col-md-6">
            <input type="text" name="search" class="form-control" placeholder="Search name, phone, email…" value="{{ request('search') }}">
        </div>
        <div class="col-md-2">
            <button class="btn btn-primary w-100">Search</button>
        </div>
    </form>

    <div class="card">
        <div class="card-body p-0">
            <table class="table table-hover mb-0">
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Phone</th>
                        <th>Email</th>
                        <th>Channels</th>
                        <th>Opted Out</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($contacts as $contact)
                    <tr>
                        <td>{{ $contact->name }}</td>
                        <td>{{ $contact->phone ?? '—' }}</td>
                        <td>{{ $contact->email ?? '—' }}</td>
                        <td>
                            @if($contact->phone)<span class="badge badge-primary mr-1">SMS</span>@endif
                            @if($contact->whatsapp_number)<span class="badge badge-success mr-1">WhatsApp</span>@endif
                            @if($contact->telegram_chat_id)<span class="badge badge-info mr-1">Telegram</span>@endif
                        </td>
                        <td>{{ $contact->opted_out ? '<span class="text-danger">Yes</span>' : 'No' }}</td>
                        <td>
                            <a href="{{ route('titanreach.contacts.edit', $contact->id) }}" class="btn btn-sm btn-outline-primary">Edit</a>
                            <form method="POST" action="{{ route('titanreach.contacts.destroy', $contact->id) }}" class="d-inline" onsubmit="return confirm('Delete?')">
                                @csrf
                                <button class="btn btn-sm btn-danger">Delete</button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="6" class="text-center text-muted py-3">No contacts found.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    <div class="mt-3">{{ $contacts->withQueryString()->links() }}</div>
</div>
@endsection
