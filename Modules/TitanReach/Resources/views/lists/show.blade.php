@extends('layouts.app')

@section('content')
<div class="content-wrapper">
    <div class="row mb-3">
        <div class="col-12 d-flex justify-content-between align-items-center flex-wrap gap-2">
            <div>
                <h4 class="mb-0">{{ $list->name }}</h4>
                @if($list->description)
                    <small class="text-muted">{{ $list->description }}</small>
                @endif
            </div>
            <a href="{{ route('titanreach.lists.index') }}" class="btn btn-outline-secondary">← Lists</a>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <div class="row">
        {{-- ── Contact table ─────────────────────────────────────────────── --}}
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    Contacts <span class="badge badge-secondary ms-1">{{ $contacts->total() }}</span>
                </div>
                <div class="card-body p-0">
                    <table class="table table-hover mb-0">
                        <thead>
                            <tr>
                                <th>Name</th>
                                <th>Phone</th>
                                <th>Email</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($contacts as $contact)
                            <tr>
                                <td>{{ $contact->name }}</td>
                                <td class="small">{{ $contact->phone ?? '—' }}</td>
                                <td class="small">{{ $contact->email ?? '—' }}</td>
                                <td>
                                    <form method="POST"
                                          action="{{ route('titanreach.lists.contacts.remove', [$list->id, $contact->id]) }}"
                                          class="d-inline"
                                          onsubmit="return confirm('Remove from list?')">
                                        @csrf
                                        <button class="btn btn-sm btn-outline-danger">Remove</button>
                                    </form>
                                </td>
                            </tr>
                            @empty
                            <tr><td colspan="4" class="text-center text-muted py-3">No contacts in this list yet.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="mt-2">{{ $contacts->links() }}</div>
        </div>

        {{-- ── Add contact ──────────────────────────────────────────────── --}}
        <div class="col-md-4">
            <div class="card">
                <div class="card-header">Add Contact</div>
                <div class="card-body">
                    <form method="POST" action="{{ route('titanreach.lists.contacts.add', $list->id) }}">
                        @csrf
                        <div class="form-group">
                            <label>Contact ID</label>
                            <input type="number" name="contact_id" class="form-control @error('contact_id') is-invalid @enderror"
                                   placeholder="Enter Contact ID" required>
                            @error('contact_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <button class="btn btn-success w-100">Add to List</button>
                    </form>
                    <hr>
                    <a href="{{ route('titanreach.contacts.index') }}" class="btn btn-outline-secondary btn-sm w-100">
                        Browse Contacts
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
