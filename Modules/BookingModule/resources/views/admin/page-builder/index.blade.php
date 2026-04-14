@extends('adminmodule::layouts.master')

@section('title', 'Booking Page Creator')

@section('content')
    <div class="d-flex flex-wrap justify-content-between align-items-center gap-3 mb-4">
        <div>
            <h2 class="page-title mb-1">Booking Page Creator</h2>
            <p class="text-muted mb-0">Create premium booking landing pages directly from the Booking module.</p>
        </div>
        <div class="d-flex flex-wrap gap-2">
            @foreach($templateOptions as $key => $template)
                <a href="{{ route('admin.booking.pages.create', ['template' => $key]) }}" class="btn btn--secondary">New {{ $template['name'] }}</a>
            @endforeach
        </div>
    </div>

    <div class="card border-0 shadow-sm">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table align-middle mb-0">
                    <thead>
                    <tr>
                        <th>Title</th>
                        <th>Slug</th>
                        <th>Status</th>
                        <th>Template</th>
                        <th>Published</th>
                        <th class="text-end">Actions</th>
                    </tr>
                    </thead>
                    <tbody>
                    @forelse($pages as $page)
                        <tr>
                            <td>
                                <div class="fw-semibold">{{ $page->title }}</div>
                                <div class="text-muted small">{{ $page->headline }}</div>
                            </td>
                            <td><code>/book/{{ $page->slug }}</code></td>
                            <td><span class="badge bg-light text-dark text-uppercase">{{ $page->status }}</span></td>
                            <td>{{ str_replace('-', ' ', $page->template) }}</td>
                            <td>{{ optional($page->published_at)->format('Y-m-d H:i') ?: '—' }}</td>
                            <td class="text-end">
                                <div class="d-flex justify-content-end gap-2 flex-wrap">
                                    <a href="{{ route('admin.booking.pages.preview', $page->slug) }}" class="btn btn-sm btn-outline-primary">Preview</a>
                                    <a href="{{ route('booking.pages.show', $page->slug) }}" class="btn btn-sm btn-outline-dark" target="_blank">Live</a>
                                    <a href="{{ route('admin.booking.pages.edit', $page->slug) }}" class="btn btn-sm btn--secondary">Edit</a>
                                    <form action="{{ route('admin.booking.pages.destroy', $page->slug) }}" method="POST" onsubmit="return confirm('Delete this booking page?')">
                                        @csrf
                                        @method('DELETE')
                                        <button class="btn btn-sm btn-outline-danger">Delete</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center py-5 text-muted">No booking pages yet.</td>
                        </tr>
                    @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="mt-3">
        {{ $pages->links() }}
    </div>
@endsection
